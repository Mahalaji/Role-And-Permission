<?php

namespace App\Http\Controllers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\str;
use App\Models\languages;

use Illuminate\Http\Request;

class language extends Controller
{
    public function getLanguageAjax(Request $request)
    {
        try {
            $query = languages::select('id', 'languagename', 'languagecode', 'created_at', 'updated_at');

            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        
           
        
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/language/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorylanguage/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
    function addlanguage(Request $request) {

        $request->validate([
            'languagename' => 'required',
            'languagecode' => 'required',

        ]);
    
        $languageadd = new languages();
        $languageadd->languagename = $request->languagename;
        $languageadd->languagecode = $request->languagecode;
    
        $languageadd->save();
    
        if ($languageadd) {
            return redirect('/language')->with('success', 'language added successfully!');
        } else {
            return back()->with('error', 'Failed to add the language.');
        }
    }
    public function editlanguage($id){
        $language = languages::find($id); 
    
        if (!$language) {
            return redirect()->back()->with('error', 'language not found');
        }
 
        if (!is_object($language)) {
            return redirect()->back()->with('error', 'Invalid language data');
        }
    
        return view('language.edit', compact('language'));
    }
    public function updatelanguage(Request $request)
    {
        $request->validate([
            'languagename' => 'required',
            'languagecode' => 'required',
        ]);
    
        $languageedit = languages::find($request->id);
    
        if (!$languageedit) {
            return redirect()->back()->withErrors(['error' => 'language not found']);
        }
    
        $languageedit->languagename = $request->languagename;
        $languageedit->languagecode = $request->languagecode;
        $languageedit->updated_at = now();
        $languageedit->created_at =$request->created_at;
    
        $languageedit->save();
    
        return redirect('/language')->with('success', 'language updated successfully');
    }
    public function destorylanguage($id){
        $language = languages::find($id); 
    
        if (!$language) {
            return redirect()->back()->withErrors(['error' => 'language not found']);
        }
    
        $language->delete();
    
        return redirect('/language')->with('success', 'language deleted successfully');
    }
}
