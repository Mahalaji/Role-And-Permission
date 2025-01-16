<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\pages;
use Illuminate\Support\str;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class Pagess extends Controller
{
    public function getPagesAjax(Request $request)
    {
        try {
            $user=Auth::user();
            $query = pages::select('id','title', 'description', 'created_at', 'updated_at');
            if (!($user->hasRole(['Admin','Page_Team']))) {
                $query->where('user_id', $user->id);
            }
    
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
    
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/editpages/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorypages/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
    public function editpages($id){
        $pages = pages::find($id); 
    
        if (!$pages) {
            return redirect()->back()->with('error', 'User not found');
        }
 
        if (!is_object($pages)) {
            return redirect()->back()->with('error', 'Invalid user data');
        }
    
        return view('Backend.pages.edit', compact('pages'));
    }
    public function updatepages(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    
        $pagesedit = pages::find($request->id);
    
        if (!$pagesedit) {
            return redirect()->back()->withErrors(['error' => 'News not found']);
        }
    
        $pagesedit->title = $request->title;
        $pagesedit->created_at = $request->created_at;
        $pagesedit->updated_at = now();
        $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->description); 
        $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
        $pagesedit->description = $cleanDescription;
        if ($pagesedit->title !== $request->title) {
            $slug = Str::slug($request->title);
            $existingSlugCount = pages::where('slug', $slug)->where('id', '!=', $request->id)->count();
    
            if ($existingSlugCount > 0) {
                $slug = $slug . '-' . time();
            }
    
            $pagesedit->slug = $slug;
        }
        $pagesedit->save();
    
        return redirect('/pages')->with('success', 'Pages updated successfully');
    }
    function createpages(Request $request) {
        $userid = Auth::user();

        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    
        $pagesadd = new pages();
        $pagesadd->title = $request->title;
        $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->description); 
    $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
    $pagesadd->description = $cleanDescription;
    $pagesadd->user_id = $userid->id;

        $slug = Str::slug($request->title);
        $existingSlugCount = pages::where('slug', $slug)->count();
        if ($existingSlugCount > 0) {
            $slug = $slug . '-' . time();
        }
        $pagesadd->slug = $slug;

        $pagesadd->save();
    
        if ($pagesadd) {
            return redirect('/pages')->with('success', 'Pages added successfully!');
        } else {
            return back()->with('error', 'Failed to add the pages.');
        }
    }
    public function destorypages($id){
        $pages = pages::find($id); 
    
        if (!$pages) {
            return redirect()->back()->withErrors(['error' => 'pages not found']);
        }
    
        $pages->delete();
    
        return redirect('/pages')->with('success', 'Pages deleted successfully');
    }
}
