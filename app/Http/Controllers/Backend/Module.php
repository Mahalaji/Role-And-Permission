<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\modules;
use Illuminate\Support\Facades\DB;
use App\Models\permissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
class Module extends Controller
{
    public function getModuleAjax(Request $request)
    {
        try {
            $query = modules::select('id', 'module_name','permission', 'parent_id', 'created_at', 'updated_at');
        
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
                    return '<a href="/mvc/create/' . $row->id . '"style="color:black"><i class="fas fa-key"></i></a>';
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
                ->rawColumns(['MVCcreate', 'delete','time_ago','time_update_ago','addpermissions'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function add_submodule($id){
            $module = modules::find($id); 
        
            if (!$module) {
                return redirect()->back()->with('error', 'Module not found');
            }
     
            if (!is_object($module)) {
                return redirect()->back()->with('error', 'Invalid module data');
            }
        
    
            return view('Backend.module.subcreate', compact('module'));
    }
public function addsubmodule(Request $request){
    
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
public function addmodule(Request $request){
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

public function moduleadd(Request $request){
    $module = modules::where('parent_id',0)->get();
    return view('Backend.module.create', compact('module'));

}

public function editmodule($id){
    // dd($id);
    $module = modules::find($id); 
    $module_name = $module->module_name;
    $module_name =ucfirst($module_name);
    // Create a Model with a Migration
    Artisan::call("make:model $module_name -m");
    
    // Create a Controller
    Artisan::call("make:controller $module_name");

    // Add index, create, and edit methods dynamically to the controller
    $controllerPath = app_path("Http/Controllers/$module_name.php");
    $controllerContent = file_get_contents($controllerPath);

    // Define the methods dynamically
    $methods = <<<EOD

    // Index method
    public function index()
    {
        return view('$module_name.index');
    }

    // Create method
    public function create()
    {
        return view('$module_name.create');
    }

    // Edit method
    public function edit()
    {
        return view('$module_name.edit');
    }

EOD;

    // Insert the methods into the controller
    $controllerContent = preg_replace(
        '/\{/',
        "{\n" . $methods,
        $controllerContent,
        1
    );

    file_put_contents($controllerPath, $controllerContent);

    // Create the directory for views if it doesn't exist
    $viewDirectory = resource_path("views/$module_name");
    if (!File::exists($viewDirectory)) {
        File::makeDirectory($viewDirectory, 0755, true); // Recursive directory creation
    }

    // Create Views
    $viewContent = "<h1>Welcome to My New View</h1>";
    file_put_contents($viewDirectory . '/index.blade.php', $viewContent);
    file_put_contents($viewDirectory . '/edit.blade.php', $viewContent);
    file_put_contents($viewDirectory . '/create.blade.php', $viewContent);

    // Clear the view cache
    Artisan::call('view:clear');

    // Register routes dynamically
    $this->registerRoutes($module_name);

    // Return a success message
    return redirect('/module');
}

private function registerRoutes($module_name)
{
    // Dynamically add routes to the `web.php` file
    $routesPath = base_path('routes/web.php');
    $routeDefinition = <<<EOD

// Routes for {$module_name}Controller
Route::get('/{$module_name}', [\App\Http\Controllers\\{$module_name}::class, 'index'])->name('{$module_name}');
Route::get('/{$module_name}/create', [\App\Http\Controllers\\{$module_name}::class, 'create'])->name('{$module_name}');
Route::get('/{$module_name}/edit', [\App\Http\Controllers\\{$module_name}::class, 'edit'])->name('{$module_name}');

EOD;

    // Append the routes if not already defined
    if (!str_contains(file_get_contents($routesPath), "Routes for {$module_name}Controller")) {
        file_put_contents($routesPath, $routeDefinition, FILE_APPEND);
    }
}

public function updatemodule(Request $request){
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
public function destorymodule($id){
    $modules = modules::find($id); 

    if (!$modules) {
        return redirect()->back()->withErrors(['error' => 'Module not found']);
    }

    $modules->delete();

    return redirect('/module')->with('success', 'module deleted successfully');
}

public function savePermissions(Request $request)
{
    $moduleId = $request->module_id;
    $guardName = $request->guard_name;
    $permissions = $request->permissions;

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
}

public function ShowPermissions(Request $request){
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
}
