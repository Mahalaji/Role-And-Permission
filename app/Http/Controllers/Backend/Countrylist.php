<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Countrylists;

use Illuminate\Http\Request;

class Countrylist extends Controller
{

    // Index method
    public function index()
    {
        return view('Backend.Countrylist.index');
    }
    public function getCountryAjax(Request $request)
    {
        
        try {
            $query = Countrylists::select('id', 'name','country_code');

            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        
           
        
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/Countrylist/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destoryCountrylist/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
    // Edit method
    public function edit($id)
    {
        $country = Countrylists::find($id); 
        return view('Backend.Countrylist.edit',compact('country'));
    }
    public function updateCountry(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'country_code' => 'required',

        ]);
    
        $countryedit = Countrylists::find($request->id);
    
        if (!$countryedit) {
            return redirect()->back()->withErrors(['error' => 'country not found']);
        }
    
        $countryedit->name = $request->name;
        $countryedit->updated_at = now();
        $countryedit->created_at =$request->created_at;
    
        $countryedit->save();
    
        return redirect('/Countrylist')->with('success', 'country updated successfully');
    }
    //delete
    public function destoryCountrylist($id){
        $Country = Countrylists::find($id); 
    
        if (!$Country) {
            return redirect()->back()->withErrors(['error' => 'Country not found']);
        }
    
        $Country->delete();
    
        return redirect('/Countrylist')->with('success', 'Country deleted successfully');
    }
}
