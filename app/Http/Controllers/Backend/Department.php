<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\departments;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class Department extends Controller
{
    public function getDepartmentAjax(Request $request)
    {
        try {
            $query = departments::select('id', 'departmentname','created_at', 'updated_at');

            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        
           
        
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/department/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorydepartment/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
                ->rawColumns(['edit', 'delete','time_ago','time_update_ago'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    function adddepartment(Request $request) {

        $request->validate([
            'departmentname' => 'required',
        ]);
    
        $departmentadd = new departments();
        $departmentadd->departmentname = $request->departmentname;
       
        $departmentadd->save();
    
        if ($departmentadd) {
            return redirect('/department')->with('success', 'Department added successfully!');
        } else {
            return back()->with('error', 'Failed to add the department.');
        }
    }
    public function editdepartment($id){
        $department = departments::find($id); 
    
        if (!$department) {
            return redirect()->back()->with('error', 'department not found');
        }
 
        if (!is_object($department)) {
            return redirect()->back()->with('error', 'Invalid department data');
        }
 
        return view('Backend.department.edit', compact('department'));
    }
    public function updateDepartment(Request $request)
    {
        $request->validate([
            'departmentname' => 'required',
        ]);
    
        $departmentedit = departments::find($request->id);
    
        if (!$departmentedit) {
            return redirect()->back()->withErrors(['error' => 'department not found']);
        }
    
        $departmentedit->departmentname = $request->departmentname;
        $departmentedit->updated_at = now();
        $departmentedit->created_at =$request->created_at;
    
        $departmentedit->save();
    
        return redirect('/department')->with('success', 'department updated successfully');
    }
    public function destorydepartment($id){
        $department = departments::find($id); 
    
        if (!$department) {
            return redirect()->back()->withErrors(['error' => 'department not found']);
        }
    
        $department->delete();
    
        return redirect('/department')->with('success', 'department deleted successfully');
    }
}
