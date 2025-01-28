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
        $inputTypes = $request->input('inputTypes', []);
        $id = $request->moduleId;
        $tablename = $request->tablename;
        $columns = $request->input('columns', []);

        // Validate the module
        $module = modules::find($id);
        if (!$module) {
            return redirect()->back()->withErrors('Module not found');
        }

        // Format module names
        $module_name = ucfirst(strtolower(str_replace(' ', '', $module->module_name)));
        $plural_module_name = $module_name . 's';

        // Paths for MVC components
        $modelPath = app_path("Models/$plural_module_name.php");
        $controllerPath = app_path("Http/Controllers/Backend/{$module_name}Controller.php");
        $viewDirectory = resource_path("views/Backend/$module_name");
        // Cleanup existing files
        $this->cleanupFiles($modelPath, $controllerPath, $viewDirectory);
        // Generate Model and Controller
        Artisan::call("make:model $plural_module_name");
        Artisan::call("make:controller Backend/{$module_name}Controller");

        $this->updateModel($modelPath, $tablename);
        // Generate Controller Methods
        $this->updateController($controllerPath, $tablename, $columns, $module_name);

        // Generate Views
        $this->generateViews($viewDirectory, $module_name, $tablename, $columns, $inputTypes);

        // Clear View Cache
        Artisan::call('view:clear');

        // Register Routes
        $this->registerRoutes($module_name);

        return redirect('/module')->with('success', 'MVC structure recreated successfully.');
    }

    protected function cleanupFiles($modelPath, $controllerPath, $viewDirectory)
    {
        if (file_exists($modelPath))
            unlink($modelPath);
        if (file_exists($controllerPath))
            unlink($controllerPath);
        if (File::exists($viewDirectory))
            File::deleteDirectory($viewDirectory);
    }

    protected function updateController($controllerPath, $tablename, $columns, $module_name)
    {
        $columnsString = implode("', '", $columns);
        $methods = <<<EOD
    // Index method
    public function index()
    {
        \$columns = ['$columnsString'];
        \$data = DB::table("$tablename")->select(\$columns)->get();
        return view('Backend.$module_name.index', compact('columns', 'data'));
    }
    
    // Create method
    public function create()
    {
        return view('Backend.$module_name.create');
    }
    
    // Edit method
    public function edit(\$id)
    {
        \$columns = ['$columnsString'];
        \$item = DB::table('$tablename')->where('id', \$id)->first();
        return view('Backend.$module_name.edit', compact('item', 'columns'));
    }
    
    // Store method
    public function store(Request \$request)
    {
        // Define dynamic validation rules
        \$rules = [];
        
        // Generate validation rules based on column types
        foreach (['$columnsString'] as \$col) {
         if (\$col == 'id') {
                continue;
            }
            \$type = \$inputTypes[\$col] ?? 'string';  // Default to 'string' if no type is specified
            
            if (\$type == 'text' || \$type == 'string') {
                \$rules[\$col] = 'required|string|max:255';
            } elseif (\$type == 'number' || \$type == 'integer') {
                \$rules[\$col] = 'required|integer';
            } elseif (\$type == 'email') {
                \$rules[\$col] = 'required|email';
            } elseif (\$type == 'date') {
                \$rules[\$col] = 'required|date';
            } else {
                \$rules[\$col] = 'required';
            }
        }

        // Validate the incoming request data
        \$validatedData = \$request->validate(\$rules);
         \$validatedData['image'] =\$request->image;
        // Insert validated data into the database
        DB::table('$tablename')->insert(\$validatedData);

        return redirect('/$module_name')->with('success', '$module_name created successfully.');
    }
    
    // Update method
    public function update(Request \$request)
    {
        // Define dynamic validation rules
        \$rules = [];
        
        // Generate validation rules based on column types
        foreach (['$columnsString'] as \$col) {
            \$type = \$inputTypes[\$col] ?? 'string';  // Default to 'string' if no type is specified
            
            // Skip the 'id' column for validation
            if (\$col == 'id') {
                continue;
            }
            
            if (\$type == 'text' || \$type == 'string') {
                \$rules[\$col] = 'required|string|max:255';
            } elseif (\$type == 'number' || \$type == 'integer') {
                \$rules[\$col] = 'required|integer';
            } elseif (\$type == 'email') {
                \$rules[\$col] = 'required|email';
            } elseif (\$type == 'date') {
                \$rules[\$col] = 'required|date';
            } else {
                \$rules[\$col] = 'required';
            }
        }

        // Validate the incoming request data
        \$validatedData = \$request->validate(\$rules);
        \$validatedData['image'] =\$request->image;
        // Update validated data in the database
        DB::table('$tablename')->where('id', \$request->id)->update(\$validatedData);

        return redirect('/$module_name')->with('success', '$module_name updated successfully.');
    }
    
    // Delete method
    public function delete(\$id)
    {
        DB::table('$tablename')->where('id', \$id)->delete();
        return redirect('/$module_name')->with('success', '$module_name deleted successfully.');
    }
EOD;


        // Read the controller content
        $controllerContent = file_get_contents($controllerPath);

        // Add use statement below the namespace declaration
        $controllerContent = preg_replace('/namespace\s+[A-Za-z0-9\\\]+;/', '$0' . "\nuse Illuminate\Support\Facades\DB;", $controllerContent);

        // Insert the methods into the controller
        $controllerContent = preg_replace('/\{/', "{\n" . $methods, $controllerContent, 1);

        // Write the updated content back to the controller file
        file_put_contents($controllerPath, $controllerContent);
    }
    protected function generateViews($viewDirectory, $module_name, $tablename, $columns, $inputTypes)
    {
        File::makeDirectory($viewDirectory, 0755, true);
    
        // Ensure 'id' is part of the columns for processing, but it won't be displayed in the table or form
        if (!in_array('id', $columns)) {
            array_unshift($columns, 'id'); // Add 'id' as the first column
        }
    
        // Index View
        $tableHeaders = '';
        $indexContent = <<<EOD
        @extends('Backend.layouts.app')
        <link rel="stylesheet" href="{{ asset('css/Backend/blog.css') }}">
        @section('content')
        <div class="info" style="background: white;">
        <div class="container mt-4">
            <h2>$module_name List</h2>
        <a href="/$module_name/create" class="btn btn-primary">Add $module_name</a>
        <table id="table">
            <thead>
                <tr>
                </div>
                </div>
        EOD;
    
        // Add table headers dynamically, excluding 'id' from being shown
        foreach ($columns as $col) {
            if ($col != 'id') {
                $tableHeaders .= "<th>" . ucfirst($col) . "</th>";
            }
        }
    
        $indexContent .= $tableHeaders . "<th>Actions</th></tr></thead><tbody>";
    
        // Add rows, excluding 'id' from being shown in the table
        $indexContent .= <<<EOD
            @foreach(\$data as \$row)
            <tr>
        EOD;
    
        foreach ($columns as $col) {
            if ($col != 'id') {
                $indexContent .= "<td>{{ \$row->$col }}</td>";
            }
        }
    
        $indexContent .= <<<EOD
                <td>
                    <a href="/$module_name/edit/{{ \$row->id }}" class="btn btn-warning">Edit</a>
                    <form action="/$module_name/delete/{{ \$row->id }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endsection
    EOD;
    
        // Create View
        $formFields = '';
        foreach ($inputTypes as $col => $type) {
            // Skip adding 'id' field to the form
            if ($col != 'id') {
                $coll = ucwords($col);
        
                if ($type == 'file') {
                    // Special handling for file input
                    $formFields .= "
                    <div class='mb-3'>
                        <label class='form-label fw-bold'>$coll</label><br>
                        <div class='d-flex flex-column align-items-center'>
                            <div class='input-group'>
                                <input type='text' id='image_label' class='form-control' name='image'
                                    placeholder='Select an image...' aria-label='Image'>
                                <button class='btn btn-outline-secondary' type='button' id='button-image'>Select</button>
                            </div>
                        </div>
                    </div>";
                } else {
                    // Default handling for other input types
                    $formFields .= " 
                    <div class='input-group'>
                        <label>$coll</label><br>
                        <input type='$type' name='$col' />
                    </div>";
                }
            }
        }
        
    
        $createContent = <<<EOD
        @extends('Backend.layouts.app')
        <link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
        @section('content')
        <main id="main" class="main">
        <h1 class="header">Create $module_name</h1>
        <form class="simple" method="post" action="/$module_name/store" enctype="multipart/form-data">
        <div class="form1">
            @csrf
            $formFields
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        </main>
        @endsection
    EOD;
    
      // Edit View
      $editFields = '';
      foreach ($inputTypes as $col => $type) {
          // Check if the column is 'id'
          if ($col == 'id') {
              // Create hidden input field for 'id'
              $editFields .= " <input type='hidden' name='$col' value='{{ \$item->$col }}' />";
          } else {
              // For other columns, create regular input fields
              $coll = ucwords($col);
      
              if ($type == 'file') {
                  // Special handling for file input (image upload)
                  $editFields .= "
                  <div class='mb-3'>
                      <label class='form-label fw-bold'>$coll</label><br>
                      <div class='d-flex flex-column align-items-center'>
                          <img src='{{ asset(\$item->$col) }}' alt='Uploaded Image' class='img-thumbnail mb-2' height='100' width='100'>
                          <div class='input-group'>
                              <input type='text' id='image_label' class='form-control' name='image'
                                  placeholder='Select an image...' aria-label='Image'>
                              <button class='btn btn-outline-secondary' type='button' id='button-image'>Select</button>
                          </div>
                      </div>
                  </div>";
              } else {
                  // Default handling for other input types
                  $editFields .= " 
                  <div class='input-group'>
                      <label>$coll</label><br>
                      <input type='$type' name='$col' value='{{ \$item->$col }}' />
                  </div>";
              }
          }
      }
      

$editContent = <<<EOD
@extends('Backend.layouts.app')
<link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
@section('content')
<main id="main" class="main">
<h1 class="header">Edit $module_name</h1>
<form class="simple" method="post" action="/$module_name/update" enctype="multipart/form-data">
<div class="form1">
    @csrf
    @method('POST')

    <input type="hidden" name="tablename" value="$tablename">

    $editFields
    <button type="submit" class="btn btn-primary">Update</button>
</div>
</form>
</main>
@endsection
EOD;

    
        // Save views
        file_put_contents("$viewDirectory/index.blade.php", $indexContent);
        file_put_contents("$viewDirectory/create.blade.php", $createContent);
        file_put_contents("$viewDirectory/edit.blade.php", $editContent);
    }
    
    private function updateModel($modelPath, $tablename)
    {
        if (file_exists($modelPath)) {
            $modelContent = file_get_contents($modelPath);

            // Add the $table property if it doesn't already exist
            if (!str_contains($modelContent, '$table')) {
                $tableProperty = "\n    protected \$table = '$tablename';\n";
                $modelContent = preg_replace('/class\s+\w+\s+extends\s+Model\s*\{/', "$0$tableProperty", $modelContent);
                file_put_contents($modelPath, $modelContent);
            }
        }
    }

    protected function registerRoutes($module_name)
    {
        $routePath = base_path('routes/web.php');

        // Read the current content of the routes file
        $routeContent = file_get_contents($routePath);

        // Regular expression to find existing routes for the module
        $pattern = "/Route::get\('\/$module_name.*?delete\('.*?\);/s";

        // Remove the existing routes if any
        $updatedRouteContent = preg_replace($pattern, '', $routeContent);

        // Append the new routes to the updated content
        $routes = <<<EOD
// $module_name Routes
Route::get('/$module_name', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'index'])->name('$module_name');
Route::get('/$module_name/create', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'create']);
Route::post('/$module_name/store', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'store']);
Route::get('/$module_name/edit/{id}', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'edit']);
Route::post('/$module_name/update', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'update']);
Route::post('/$module_name/delete/{id}', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'delete']);
EOD;

        // Append the new routes to the file
        file_put_contents($routePath, $updatedRouteContent . $routes);
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
