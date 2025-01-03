<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\str;
use Illuminate\Support\Facades\Auth;
use App\Models\news;
use App\Models\newscategory;
use App\Models\domains;
use App\Models\languages;

class newss extends Controller
{
    public function title()
    {
        $titles = newscategory::select('title','id')->get();
        $domain = domains::select('domainname','id')->get();
        $language = languages::select('languagename','id')->get();
        return view('news.create', compact('titles','domain','language'));
    }
    function createnews(Request $request) {
        $userid = Auth::user();

        $request->validate([
            'title' => 'required',
            'name' => 'required',
            'email' => 'required',
            'category_id' => 'required',
            'news_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
            'seo_title' => 'required',
            'meta_keyword' => 'required',
            'seo_robat' => 'required',
            'meta_description' => 'required',
            'language' => 'required',
            'domain' => 'required',
        ]);
    
        $newsadd = new news();
        $newsadd->title = $request->title;
        $newsadd->name = $request->name;
        $newsadd->email = $request->email;
        $newsadd->category_id = $request->category_id;
        $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->description); 
    $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
    $newsadd->description = $cleanDescription;
        $newsadd->seo_title =$request->seo_title;
        $newsadd->meta_keyword =$request->meta_keyword;
        $newsadd->seo_robat =$request->seo_robat;
        $newsadd->meta_description =$request->meta_description;
        $newsadd->user_id = $userid->id;
        $newsadd->language_id = $request->language;
        $newsadd->domain_id = $request->domain;

    
        if ($request->hasFile('news_image')) {
            $Image = $request->file('news_image');
            $imageName = time() . '_' . $Image->getClientOriginalName();
            $imagePath = 'news_images/' . $imageName;
            $Image->move(public_path('news_images'), $imageName);
            $newsadd->news_image = $imagePath;
        }
    
        $slug = Str::slug($request->title);
        $existingSlugCount = news::where('slug', $slug)->count();
        if ($existingSlugCount > 0) {
            $slug = $slug . '-' . time();
        }
        $newsadd->slug = $slug;
    
        $newsadd->save();
    
        if ($newsadd) {
            return redirect('/newss')->with('success', 'News added successfully!');
        } else {
            return back()->with('error', 'Failed to add the news.');
        }
    }
    public function getNewsAjax(Request $request)
    {
        try {
            $user=Auth::user();
            $query = news::select('id', 'name', 'title', 'category_id', 'domain_id','language_id', 'created_at', 'updated_at')->with('categories','domain','language');
            if (!($user->hasRole(['Admin','News_Team']))) {
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
                    return '<a href="/news/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorynews/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
    public function editnews($id){
        $news = news::find($id); 
    
        if (!$news) {
            return redirect()->back()->with('error', 'User not found');
        }
 
        if (!is_object($news)) {
            return redirect()->back()->with('error', 'Invalid user data');
        }
    
        $titles = newscategory::select('title','id')->get();
        $domain = domains::select('domainname','id')->get();
        $language = languages::select('languagename','id')->get();
        return view('news.edit', compact('news', 'titles','domain','language'));
    }
    public function updatenews(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'name' => 'required',
            'email' => 'required',
            'category_id' => 'required',
            'news_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
            'seo_title' => 'required',
            'meta_keyword' => 'required',
            'seo_robat' => 'required',
            'meta_description' => 'required',
            'language' => 'required',
            'domain' => 'required',
        ]);
    
        $newsedit = news::find($request->id);
    
        if (!$newsedit) {
            return redirect()->back()->withErrors(['error' => 'News not found']);
        }
    
        $newsedit->title = $request->title;
        $newsedit->email = $request->email;
        $newsedit->name = $request->name;
        $newsedit->category_id = $request->category_id;
        $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->description); 
        $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
        $newsedit->description = $cleanDescription;
        $newsedit->updated_at = now();
        $newsedit->seo_title =$request->seo_title;
        $newsedit->meta_keyword =$request->meta_keyword;
        $newsedit->seo_robat =$request->seo_robat;
        $newsedit->meta_description =$request->meta_description;
        $newsedit->created_at =$request->created_at;
        $newsedit->language_id =$request->language;
        $newsedit->domain_id =$request->domain;
    
        if ($request->hasFile('news_image')) {
            $Image = $request->file('news_image');
            $imageName = time() . '_' . $Image->getClientOriginalName();
            $imagePath = 'news_images/' . $imageName;
            $Image->move(public_path('news_images'), $imageName);
            $newsedit->news_image = $imagePath;
        }
    
        if ($newsedit->title !== $request->title) {
            $slug = Str::slug($request->title);
            $existingSlugCount = news::where('slug', $slug)->where('id', '!=', $request->id)->count();
    
            if ($existingSlugCount > 0) {
                $slug = $slug . '-' . time();
            }
    
            $newsedit->slug = $slug;
        }
    
        $newsedit->save();
    
        return redirect('/newss')->with('success', 'News updated successfully');
    }
    public function destorynews($id){
        $news = news::find($id); 
    
        if (!$news) {
            return redirect()->back()->withErrors(['error' => 'News not found']);
        }
    
        $news->delete();
    
        return redirect('/newss')->with('success', 'News deleted successfully');
    }
    public function getNewsCategoryAjax(Request $request){
        try {
            $query = newscategory::select('id', 'title');
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/newscategory/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorynewscategory/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-sm btn-danger"style="border: none; outline: none;"><i class="fas fa-trash"></i></button>
                            </form>';
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function createnewscategory(Request $request){
        $request->validate([
            'title' => 'required',
        ]);
    
        $categeryadd = new newscategory();
        $categeryadd->title = $request->title;
    
        $categeryadd->save();
    
        if ($categeryadd) {
            return redirect('/newscategory')->with('success', 'Category added successfully!');
        } else {
            return back()->with('error', 'Failed to add the blog.');
        }
    }
    public function editnewscategory($id){
        $newscategory = newscategory::find($id); 
    
        if (!$newscategory) {
            return redirect()->back()->with('error', 'User not found');
        }
    
        if (!is_object($newscategory)) {
            return redirect()->back()->with('error', 'Invalid user data');
        }
    
        return view('news.category_edit', compact('newscategory'));
    }
    public function updatenewscategery(Request $request)
{
    $request->validate([
        'title' => 'required',
    ]);

    $categoryupdate = newscategory::find($request->id);

    if (!$categoryupdate) {
        return redirect()->back()->withErrors(['error' => 'news category not found']);
    }

    $categoryupdate->title = $request->title;

    $categoryupdate->save();

    return redirect('/newscategory')->with('success', 'News category updated successfully');
}
public function destorynewscategory($id){
    $newscategory = newscategory::find($id); 

    if (!$newscategory) {
        return redirect()->back()->withErrors(['error' => 'User not found']);
    }

    $newscategory->delete();

    return redirect('/newscategory')->with('success', 'User deleted successfully');
}
}