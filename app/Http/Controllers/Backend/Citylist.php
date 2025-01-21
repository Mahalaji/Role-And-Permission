<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Citylists;

class Citylist extends Controller
{

    // Index method
    public function index()
    {
        return view('Backend.Citylist.index');
    }
    public function getCityAjax(Request $request)
    {
        
        try {
            $query = Citylists::select('id', 'name');

            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        
           
        
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/City/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destoryCity/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
        return view('Backend.Citylist.create');
    }

    // Edit method
    public function edit($id)
    {
        $city =citylists::find($id);
        return view('Backend.Citylist.edit',compact('city'));
    }

    public function updateCity(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
    
        $cityedit = Citylists::find($request->id);
    
        if (!$cityedit) {
            return redirect()->back()->withErrors(['error' => 'city not found']);
        }
    
        $cityedit->name = $request->name;
        $cityedit->updated_at = now();
        $cityedit->created_at =$request->created_at;
    
        $cityedit->save();
    
        return redirect('/Citylist')->with('success', 'city updated successfully');
    }

    //delete
    public function destoryCity($id){
        $City = Citylists::find($id); 
    
        if (!$City) {
            return redirect()->back()->withErrors(['error' => 'City not found']);
        }
    
        $City->delete();
    
        return redirect('/Citylist')->with('success', 'City deleted successfully');
    }
}
