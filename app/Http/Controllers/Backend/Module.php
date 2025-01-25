<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\modules;
use App\Models\menus;
use Illuminate\Support\Facades\DB;
use App\Models\permissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\log;
use Illuminate\Support\Facades\Schema;

class Module extends Controller
{
    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];
        foreach ($tables as $table) {
            foreach ($table as $tableName) {
                $tableNames[] = $tableName;
            }
        }

        return view('Backend.module.index', compact('tableNames'));
    }
    public function mvctable(Request $request)
    {
        $moduleId = $request->input('moduleId');
        $tablename = $request->tableName;
        $columns = Schema::getColumnListing($tablename);
        return view('Backend.module.mvctable', compact('columns', 'moduleId', 'tablename'));
    }
    public function getModuleAjax(Request $request)
    {
        try {
            $query = modules::select('id', 'module_name', 'permission', 'parent_id', 'created_at', 'updated_at');
            $query = modules::where('deletestatus', 1);
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }



            return DataTables::of($query)
                // ->addColumn('addpermissions', function ($row) {
                //     return '<button class="btn btn-sm btn-success" id="permissionsbtn" data-module-id="' . $row->id . '">
                //                 <i class="fas fa-key"></i> 
                //             </button>';
                // })
                ->addColumn('addpermissions', function ($row) {
                    return '<button class="btn btn-sm btn-success" id="permissionsbtn" data-module-id="' . $row->id . '">
                           Add permission
                        </button>';
                })
                // ->addColumn('add', function ($row) {
                //     return '<a href="/submodule/add/' . $row->id . '"style="color:black">Add Module</a>';
                // })
                // ->addColumn('addpermission', function ($row) {
                //     return '<a href="/module/permission/add/' . $row->id . '"style="color:black">Add Permission</a>';
                // })
                ->addColumn('MVCcreate', function ($row) {
                    return "<button class='editModuleButton btn btn-primary' data-id='{$row->id}'>MVC</button>";
                })

                ->addColumn('delete', function ($row) {
                    return '<form action="/destorymodule/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-sm btn-danger" style="border: none; outline: none;"><i class="fas fa-trash"></i></button>
                            </form>';
                })
                ->addColumn('time_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->diffForHumans();
                })
                ->addColumn('time_update_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->diffForHumans();
                })
                ->rawColumns(['MVCcreate', 'delete', 'time_ago', 'time_update_ago', 'addpermissions'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function add_submodule($id)
    {
        $module = modules::find($id);

        if (!$module) {
            return redirect()->back()->with('error', 'Module not found');
        }

        if (!is_object($module)) {
            return redirect()->back()->with('error', 'Invalid module data');
        }


        return view('Backend.module.subcreate', compact('module'));
    }
    public function addsubmodule(Request $request)
    {

        $userid = Auth::user();

        $request->validate([
            'module_name' => 'required',
        ]);
        $moduleadd = new modules();
        $moduleadd->module_name = $request->module_name;
        $moduleadd->parent_id = $request->id;
        $moduleadd->user_id = $userid->id;

        $moduleadd->save();

        if ($moduleadd) {
            return redirect('/module')->with('success', 'Module added successfully!');
        } else {
            return back()->with('error', 'Failed to add the module.');
        }
    }
    public function addmodule(Request $request)
    {
        $userid = Auth::user();

        $request->validate([
            'module_name' => 'required',
        ]);

        $moduleadd = new modules();
        $moduleadd->module_name = $request->module_name;
        $moduleadd->user_id = $userid->id;

        $moduleadd->parent_id = $request->parent_id ?? 0;

        $moduleadd->save();

        if ($moduleadd) {
            return redirect('/module')->with('success', 'Module added successfully!');
        } else {
            return back()->with('error', 'Failed to add the module.');
        }
    }

    public function moduleadd(Request $request)
    {
        $module = modules::where('parent_id', 0)->get();
        return view('Backend.module.create', compact('module'));

    }

    public function mvc(Request $request)
    {
        $id = $request->moduleId;
        $tablename = $request->tablename;
        $columns = $request->input('columns', []); // Default to an empty array if no columns are selected

        // Validate that the module exists
        $module = modules::find($id);
        if (!$module) {
            return redirect()->back()->withErrors('Module not found');
        }

        // Format the module name
        $module_name = strtolower(str_replace(' ', '', $module->module_name));
        $module_name = ucwords($module_name);

        // Define paths and names
        $plural_module_name = $module_name . 's';
        $modelPath = app_path("Models/$plural_module_name.php");
        $controllerPath = app_path("Http/Controllers/Backend/$module_name.php");
        $viewDirectory = resource_path("views/Backend/$module_name");

        // Delete existing files if they exist
        if (file_exists($modelPath)) {
            unlink($modelPath);
        }

        if (file_exists($controllerPath)) {
            unlink($controllerPath);
        }


        if (File::exists($viewDirectory)) {
            File::deleteDirectory($viewDirectory);
        }

        // Create a Model with a Migration
        Artisan::call("make:model $plural_module_name ");

        // Create a Controller
        Artisan::call("make:controller Backend/$module_name");

        // Read the controller content
        $controllerContent = file_get_contents($controllerPath);

        // Ensure DB facade is included after the namespace declaration
        $useDB = "use Illuminate\\Support\\Facades\\DB;\n";
        if (!str_contains($controllerContent, $useDB)) {
            $controllerContent = preg_replace('/namespace\s+.*?;/', "$0\n$useDB", $controllerContent, 1);
        }

        // Define the methods dynamically
        $columnsString = implode("', '", $columns);
        $methods = <<<EOD
    
        // Index method
        public function index()
        {
            \$columns = ['$columnsString']; // Array of selected columns
            \$data = DB::table("$tablename")->select(\$columns)->get(); // Fetch data from the specified table
            return view('Backend.$module_name.index', ['columns' => \$columns, 'data' => \$data]); // Pass as an array
        }
    
        // Create method
        public function create()
        {
            return view('Backend.$module_name.create');
        }
    
        // Edit method
        public function edit(\$id)
        {
            \$item = DB::table('$tablename')->where('id', \$id)->first(); // Fetch the item to edit
            return view('Backend.$module_name.edit', ['item' => \$item]);
        }
    
    EOD;

        // Insert the methods into the controller
        $controllerContent = preg_replace(
            '/\{/',
            "{\n" . $methods,
            $controllerContent,
            1
        );

        // Write the updated content back to the controller file
        file_put_contents($controllerPath, $controllerContent);

        // Create the directory for views
        File::makeDirectory($viewDirectory, 0755, true);

        // Generate the table in the index view
        $tableHeaders = '';
        foreach ($columns as $column) {
            $tableHeaders .= "<th>" . ucwords($column) . "</th>";
        }

        // Fetch data from the database for the given table
        $data = DB::table($tablename)->select($columns)->get();

        $tableData = '';
        foreach ($data as $row) {
            $tableData .= "<tr>";
            foreach ($columns as $column) {
                $tableData .= "<td>" . htmlspecialchars($row->$column) . "</td>";
            }
            $tableData .= "<td>
                <a href='/$module_name/edit/" . $row->id . "' class='btn btn-primary btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                    <i class='fas fa-edit'></i> Edit
                </a>
            </td>";
            $tableData .= "<td>
                <form action='/$module_name/delete/" . $row->id . "' method='POST' style='display:inline;'>
                    <button type='submit' class='btn btn-sm' style='background: azure; border-radius: 7px; border: 1px solid grey; color: black;'>
                        <i class='fas fa-trash'></i> Delete
                    </button>
                </form>
            </td>";
            $tableData .= "</tr>";
        }



        $viewContent = <<<EOD
        @extends('Backend.layouts.app')
        <link rel="stylesheet" href="{{ asset('css/Backend/blog.css') }}">
        @section('content')
    <h1>$module_name List</h1>
      <div class="left" >
            <a href="{{ asset('/$module_name/create') }}">Add-$module_name</a>
        </div>
    <table id="Table" class="table table-bordered">
        <thead>
            <tr>
                $tableHeaders
            <th>Edit</th>
            <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            $tableData
        </tbody>
    </table>
    @endsection
    EOD;

        $createFormFields = '';
        foreach ($columns as $column) {
            if (strtolower($column) === 'id') {
                continue;
            }

            $formattedColumn = ucwords($column);

            $createFormFields .= <<<EOD
        <div class="form-group">
            <label for="$column">{$formattedColumn}</label>
            <input type="text" class="form-control" id="$column" name="$column" placeholder="Enter $formattedColumn">
        </div>
        EOD;
        }


        $createViewContent = <<<EOD
    @extends('Backend.layouts.app')
    <link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
    @section('content')
    <main id="main" class="main">
    <h1 class="header">Create $module_name</h1>
    <form action="/$module_name/store" method="POST">
    <div class="form1">
        @csrf
        $createFormFields
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    @endsection
    EOD;

        // Create view files
        file_put_contents($viewDirectory . '/index.blade.php', $viewContent);
        file_put_contents($viewDirectory . '/edit.blade.php', "<h1>Edit $module_name</h1>");
        file_put_contents($viewDirectory . '/create.blade.php', $createViewContent);

        // Clear the view cache
        Artisan::call('view:clear');

        // Register routes dynamically
        $this->registerRoutes($module_name);

        // Return a success message
        return redirect('/module')->with('success', 'MVC structure recreated successfully.');
    }

    private function registerRoutes($module_name)
    {
        // Dynamically add routes to the `web.php` file
        $routesPath = base_path('routes/web.php');
        $routeDefinition = <<<EOD

// Routes for {$module_name}Controller
Route::get('/{$module_name}', [\App\Http\Controllers\Backend\\{$module_name}::class, 'index'])->name('{$module_name}');
Route::get('/{$module_name}/create', [\App\Http\Controllers\Backend\\{$module_name}::class, 'create']);
Route::get('/{$module_name}/edit/{id}', [\App\Http\Controllers\Backend\\{$module_name}::class, 'edit']);
Route::get('/{$module_name}/delete/{id}', [\App\Http\Controllers\Backend\\{$module_name}::class, 'delete']);
Route::post('/{$module_name}/store', [\App\Http\Controllers\Backend\\{$module_name}::class, 'store']);



EOD;

        // Append the routes if not already defined
        if (!str_contains(file_get_contents($routesPath), "Routes for {$module_name}Controller")) {
            file_put_contents($routesPath, $routeDefinition, FILE_APPEND);
        }
    }

    public function updatemodule(Request $request)
    {
        $request->validate([
            'module_name' => 'required',
        ]);

        $moduledit = modules::find($request->id);

        if (!$moduledit) {
            return redirect()->back()->withErrors(['error' => 'Module not found']);
        }

        $moduledit->module_name = $request->module_name;
        $moduledit->created_at = $request->created_at;
        $moduledit->updated_at = now();


        $moduledit->save();

        return redirect('/module')->with('success', 'Module updated successfully');
    }
    public function restoremodule($id)
    {
        $modulesdata = modules::find($id);

        $menuData = Menus::where('id', 4)->first();
        if (!$menuData) {
            return redirect()->back()->withErrors('Module not found.');
        }
        $modulesdata->deletestatus = 1;
        $modulesdata->save();
        $jsonoutput = json_decode($menuData->json_output, true);

        $this->recovermoduledata($jsonoutput, $id);

        $menuData->json_output = json_encode($jsonoutput);
        if ($menuData->save()) {
            return redirect()->back()->with('success', 'Module delete successful');
        } else {
            return redirect()->back()->withErrors('Module delete unsuccessful');
        }
    }
    public function destorymodule($id)
    {
        $modulesdata = modules::find($id);

        $menuData = Menus::where('id', 4)->first();
        if (!$menuData) {
            return redirect()->back()->withErrors('Module not found.');
        }
        $modulesdata->deletestatus = 0;
        $modulesdata->save();
        $jsonoutput = json_decode($menuData->json_output, true);

        $this->markModuleAsDeleted($jsonoutput, $id);

        $menuData->json_output = json_encode($jsonoutput);
        if ($menuData->save()) {
            return redirect()->back()->with('success', 'Module delete successful');
        } else {
            return redirect()->back()->withErrors('Module delete unsuccessful');
        }
    }

    private function markModuleAsDeleted(&$menuItems, $moduleId)
    {
        foreach ($menuItems as &$item) {
            if ($item['moduleid'] == $moduleId) {
                $item['deletestatus'] = '0';
                return;
            }

            if (!empty($item['children'])) {
                foreach ($item['children'] as &$child) {
                    if ($child['moduleid'] == $moduleId) {
                        $child['deletestatus'] = '0';
                        return;
                    }
                }
            }
        }
    }

    private function recovermoduledata(&$menuItems, $moduleId)
    {
        foreach ($menuItems as &$item) {
            if ($item['moduleid'] == $moduleId) {
                $item['deletestatus'] = '1';
                return;
            }

            if (!empty($item['children'])) {
                foreach ($item['children'] as &$child) {
                    if ($child['moduleid'] == $moduleId) {
                        $child['deletestatus'] = '1';
                        return;
                    }
                }
            }
        }
    }
    // public function destorymodule($id)
    // {
    //     $modules = modules::find($id);

    //     if (!$modules) {
    //         return redirect()->back()->withErrors(['error' => 'Module not found']);
    //     }

    //     $modules->deletestatus = 0;
    //     $modules->save();

    //     return redirect('/module')->with('success', 'module deleted successfully');
    // }

    public function savePermissions(Request $request)
    {
        try {
            $moduleId = $request->module_id;
            $guardName = $request->guard_name;
            $permissions = $request->permissions;

            // Validate input
            if (!$permissions || !is_array($permissions)) {
                throw new \Exception('Permissions data is invalid or missing.');
            }

            foreach ($permissions as $permission) {
                if (isset($permission['id'])) {
                    // Update existing permission
                    DB::table('permissions')
                        ->where('id', $permission['id'])
                        ->update([
                            'name' => $permission['name'],
                            'module_id' => $moduleId,
                            'guard_name' => $guardName,
                            'updated_at' => now(),
                        ]);
                } else {
                    // Create new permission
                    DB::table('permissions')->insert([
                        'name' => $permission['name'],
                        'module_id' => $moduleId,
                        'guard_name' => $guardName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Log the detailed error
            Log::error('Error saving permissions: ' . $e->getMessage(), [
                'module_id' => $request->module_id,
                'guard_name' => $request->guard_name,
                'permissions' => $request->permissions,
            ]);

            // Return a detailed error response
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function ShowPermissions(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|integer',
        ]);
        $moduledata = permissions::where('module_id', $request->input('module_id'))->get();
        return response()->json(['status' => 'success', 'data' => $moduledata]);
    }

    public function deletePermission(Request $request)
    {
        $validated = $request->validate([
            'permission_id' => 'required|integer',
        ]);

        try {
            $permission = permissions::findOrFail($validated['permission_id']);
            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting permission.',
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function getModuleRecycleAjax(Request $request)
    {
        try {
            $query = modules::select('id', 'module_name', 'parent_id', 'created_at', 'updated_at');
            $query = modules::where('deletestatus', 0);
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }



            return DataTables::of($query)

                ->addColumn('restore', function ($row) {
                    return '<form action="/restoremodule/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-primary" style="border: none; outline: none;"><i class="fas fa-trash-restore"></i></button>
                            </form>';
                })
                ->addColumn('time_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->diffForHumans();
                })
                ->addColumn('time_update_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->diffForHumans();
                })
                ->rawColumns(['restore', 'time_ago', 'time_update_ago'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // public function restoremodule($id)
    // {
    //     $modules = modules::find($id);

    //     if (!$modules) {
    //         return redirect()->back()->withErrors(['error' => 'Module not found']);
    //     }

    //     $modules->deletestatus = 1;
    //     $modules->save();

    //     return redirect('/module')->with('success', 'module deleted successfully');
    // }
}
