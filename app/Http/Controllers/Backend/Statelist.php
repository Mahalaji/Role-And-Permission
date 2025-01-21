<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Statelists;
class Statelist extends Controller
{

    // Index method
    public function index()
    {
        return view('Backend.Statelist.index');
    }

    public function getStateAjax(Request $request)
    {
        
        try {
            $query = Statelists::select('id', 'name');

            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        
           
        
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/State/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destoryState/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-sm btn-danger" style="border: none; outline: none;"><i class="fas fa-trash"></i></button>
                            </form>';
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    // Create method
    public function create()
    {
        return view('Backend.Statelist.create');
    }

    // Edit method
    public function edit($id)
    {
        $state = Statelists::find($id);
        return view('Backend.Statelist.edit',compact('state'));
    }

    public function updatestate(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
    
        $stateedit = Statelists::find($request->id);
    
        if (!$stateedit) {
            return redirect()->back()->withErrors(['error' => 'state not found']);
        }
    
        $stateedit->name = $request->name;
        $stateedit->updated_at = now();
        $stateedit->created_at =$request->created_at;
    
        $stateedit->save();
    
        return redirect('/Statelist')->with('success', 'state updated successfully');
    }
    //delete
    public function destoryState($id){
        $state = Statelists::find($id); 
    
        if (!$state) {
            return redirect()->back()->withErrors(['error' => 'state not found']);
        }
    
        $state->delete();
    
        return redirect('/Statelist')->with('success', 'state deleted successfully');
    }
}
