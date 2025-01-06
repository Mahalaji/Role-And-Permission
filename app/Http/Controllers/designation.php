<?php

namespace App\Http\Controllers;
use App\Models\designations;
use App\Models\departments;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class designation extends Controller
{
    public function getDesignationAjax(Request $request)
    {
        try {
            $query = designations::select('id', 'designationname','department_id','level','created_at', 'updated_at')->with('department');

            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        
           
        
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/designation/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorydesignation/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
    public function designationadd()
    {
        $departments = departments::select('departmentname','id')->get();
        return view('designation.create', compact('departments'));
    } 
    function adddesignation(Request $request) {

        $request->validate([
            'designationname' => 'required',
            'department_id' => 'required',
            'level' => 'required',
        ]);
    
        $designationadd = new designations();
        $designationadd->designationname = $request->designationname;
        $designationadd->department_id = $request->department_id;
        $designationadd->level = $request->level;
       
        $designationadd->save();
    
        if ($designationadd) {
            return redirect('/designation')->with('success', 'designation added successfully!');
        } else {
            return back()->with('error', 'Failed to add the designation.');
        }
    }
    public function editdesignation($id){
        $designation = designations::find($id); 
    
        if (!$designation) {
            return redirect()->back()->with('error', 'designation not found');
        }
 
        if (!is_object($designation)) {
            return redirect()->back()->with('error', 'Invalid designation data');
        }
        $departments = departments::select('departmentname','id')->get();
 
        return view('designation.edit', compact('designation','departments'));
    }
    public function updateDesignation(Request $request)
    {
        $request->validate([
            'designationname' => 'required',
            'department_id' => 'required',
            'level' => 'required',
        ]);
    
        $designationedit = designations::find($request->id);
    
        if (!$designationedit) {
            return redirect()->back()->withErrors(['error' => 'designation not found']);
        }
    
        $designationedit->designationname = $request->designationname;
        $designationedit->department_id = $request->department_id;
        $designationedit->level = $request->level;
        $designationedit->updated_at = now();
        $designationedit->created_at =$request->created_at;
    
        $designationedit->save();
    
        return redirect('/designation')->with('success', 'designation updated successfully');
    }
    public function destorydesignation($id){
        $designation = designations::find($id); 
    
        if (!$designation) {
            return redirect()->back()->withErrors(['error' => 'designation not found']);
        }
    
        $designation->delete();
    
        return redirect('/designation')->with('success', 'designation deleted successfully');
    }
}
