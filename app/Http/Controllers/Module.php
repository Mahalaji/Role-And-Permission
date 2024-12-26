<?php

namespace App\Http\Controllers;
use Yajra\DataTables\Facades\DataTables;
use App\Models\modules;
use Illuminate\Support\Facades\DB;
use App\Models\permissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
                ->addColumn('edit', function ($row) {
                    return '<a href="/module/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
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
                ->rawColumns(['edit', 'delete','time_ago','time_update_ago','addpermissions'])
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
        
    
            return view('module.subcreate', compact('module'));
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

// public function add_permission($id){
//     $permission = modules::find($id); 

//     if (!$permission) {
//         return redirect()->back()->with('error', 'Module not found');
//     }

//     if (!is_object($permission)) {
//         return redirect()->back()->with('error', 'Invalid module data');
//     }


//     return view('module.permission_create', compact('permission'));
// }
// public function addpermission(Request $request){
    
//     $userid = Auth::user();

//     $request->validate([
//         'permission' => 'required',
//     ]);
//     $moduleadd = new modules();
//     $moduleadd->permission = $request->permission;
//     $moduleadd->module_name = $request->module_name;
//     $moduleadd->parent_id = $request->id;
//     $moduleadd->user_id = $userid->id;

//     $moduleadd->save();

//     if ($moduleadd) {
//         return redirect('/module')->with('success', 'Module added successfully!');
//     } else {
//         return back()->with('error', 'Failed to add the module.');
//     }
// }
public function moduleadd(Request $request){
    $module = modules::where('parent_id',0)->get();
    return view('module.create', compact('module'));

}

public function editmodule($id){
    $editmodule = modules::find($id); 
        
            if (!$editmodule) {
                return redirect()->back()->with('error', 'Module not found');
            }
     
            if (!is_object($editmodule)) {
                return redirect()->back()->with('error', 'Invalid module data');
            }
        
    
            return view('module.edit', compact('editmodule'));
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
