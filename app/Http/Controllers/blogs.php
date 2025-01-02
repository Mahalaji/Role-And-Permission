<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blogcategory;
use App\Models\domains;
use App\Models\languages;

use Yajra\DataTables\Facades\DataTables;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\str;
use Illuminate\Support\Facades\Auth;
class blogs extends Controller
{

    public function title()
    {
        $titles = Blogcategory::select('title','id')->get();
        $domain = domains::select('domainname')->get();
        $language = languages::select('languagename')->get();

        return view('blog.create', compact('titles','domain','language'));
    }   
        function addblog(Request $request) {
            $userid = Auth::user();
        $request->validate([
            'title' => 'required',
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
            'seo_title' => 'required',
            'meta_keyword' => 'required',
            'seo_robat' => 'required',
            'meta_description' => 'required',
            'language' => 'required',
            'domain' => 'required',


        ]);
        $Blogadd = new Blog();
        $Blogadd->title = $request->title;
        $Blogadd->name = $request->name;
        $Blogadd->category_id = $request->category_id;
        $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->description); 
    $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
    $Blogadd->description = $cleanDescription;
        $Blogadd->seo_title =$request->seo_title;
        $Blogadd->meta_keyword =$request->meta_keyword;
        $Blogadd->seo_robat =$request->seo_robat;
        $Blogadd->meta_description =$request->meta_description;
        $Blogadd->user_id = $userid->id;
        $Blogadd->language = $request->language;
        $Blogadd->domain = $request->domain;

    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'images/' . $imageName;
            $image->move(public_path('images'), $imageName);
            $Blogadd->image = $imagePath;
        }
    
        $slug = Str::slug($request->title);
        $existingSlugCount = Blog::where('slug', $slug)->count();
        if ($existingSlugCount > 0) {
            $slug = $slug . '-' . time();
        }
        $Blogadd->slug = $slug;
    
        $Blogadd->save();
    
        if ($Blogadd) {
            return redirect('/blog')->with('success', 'Blog added successfully!');
        } else {
            return back()->with('error', 'Failed to add the blog.');
        }
    }
    public function getBlogsAjax(Request $request)
    { 

        try {
            $user = Auth::user();
            $query = Blog::select('id', 'name', 'title', 'category_id', 'domain','language', 'created_at', 'updated_at')
                ->with('categories');
            if (!($user->hasRole(['Admin','Blog_Team']))) {
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
                    return '<a href="/blog/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destory/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
    public function edit($id){
        $blog = Blog::find($id); 
    
        if (!$blog) {
            return redirect()->back()->with('error', 'User not found');
        }
 
        if (!is_object($blog)) {
            return redirect()->back()->with('error', 'Invalid user data');
        }
    
        $titles = Blogcategory::select('title','id')->get();
        $domain = domains::select('domainname')->get();
        $language = languages::select('languagename')->get();
        return view('blog.edit', compact('blog', 'titles','domain','language'));
    }
    public function update(Request $request)
{
    $request->validate([
        'title' => 'required',
        'name' => 'required',
        'category_id' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'required',
        'seo_title' => 'required',
        'meta_keyword' => 'required',
        'seo_robat' => 'required',
        'meta_description' => 'required',
        'language' => 'required',
        'domain' => 'required',
    ]);

    $Blogedit = Blog::find($request->id);

    if (!$Blogedit) {
        return redirect()->back()->withErrors(['error' => 'Blog not found']);
    }

    $Blogedit->title = $request->title;
    $Blogedit->name = $request->name;
    $Blogedit->language = $request->language;
    $Blogedit->domain = $request->domain;
    $Blogedit->category_id = $request->category_id;
    $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->description); // Remove <p> and <strong> tags
    $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription); // Replace &nbsp; with a space and &#39; with a single quote
    $Blogedit->description = $cleanDescription;
    $Blogedit->seo_title = $request->seo_title;
    $Blogedit->meta_keyword = $request->meta_keyword;
    $Blogedit->seo_robat = $request->seo_robat;
    $Blogedit->meta_description = $request->meta_description;
    $Blogedit->created_at = $request->created_at;
    $Blogedit->updated_at = now();

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('images'), $imageName);
        $Blogedit->image = 'images/' . $imageName;
    }

    if ($Blogedit->Title !== $request->title) {
        $slug = Str::slug($request->title);
        $existingSlugCount = Blog::where('slug', $slug)->where('id', '!=', $request->id)->count();

        if ($existingSlugCount > 0) {
            $slug = $slug . '-' . time();
        }

        $Blogedit->slug = $slug;
    }

    $Blogedit->save();

    return redirect('/blog')->with('success', 'Blog updated successfully');
}
public function destory($id){
    $blogs = Blog::find($id); 

    if (!$blogs) {
        return redirect()->back()->withErrors(['error' => 'User not found']);
    }

    $blogs->delete();

    return redirect('/blog')->with('success', 'User deleted successfully');
}
public function getBlogsCategoryAjax(Request $request){
    try {
        $query = Blogcategory::select('id', 'title')->withCount('blogs');
        return DataTables::of($query)
            ->addColumn('edit', function ($row) {
                return '<a href="/blogcategory/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
            })
            ->addColumn('delete', function ($row) {
                return '<form action="/destorycategory/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
public function addcategery(Request $request){
    $request->validate([
        'title' => 'required',
    ]);

    $categeryadd = new Blogcategory();
    $categeryadd->title = $request->title;

    $categeryadd->save();

    if ($categeryadd) {
        return redirect('/blogcategory')->with('success', 'Category added successfully!');
    } else {
        return back()->with('error', 'Failed to add the blog.');
    }
}
public function destorycategory($id){
    $blogcategory = Blogcategory::find($id); 

    if (!$blogcategory) {
        return redirect()->back()->withErrors(['error' => 'User not found']);
    }

    $blogcategory->delete();

    return redirect('/blogcategory')->with('success', 'User deleted successfully');
}
public function editcategory($id){
    $blogcategory = Blogcategory::find($id); 

    if (!$blogcategory) {
        return redirect()->back()->with('error', 'User not found');
    }

    if (!is_object($blogcategory)) {
        return redirect()->back()->with('error', 'Invalid user data');
    }

    return view('blog.category_edit', compact('blogcategory'));
}
public function updateCategory(Request $request)
{
    $request->validate([
        'title' => 'required',
    ]);

    $categoryupdate = Blogcategory::find($request->id);

    if (!$categoryupdate) {
        return redirect()->back()->withErrors(['error' => 'Blog category not found']);
    }

    $categoryupdate->title = $request->title;

    $categoryupdate->save();

    return redirect('/blogcategory')->with('success', 'Blog category updated successfully');
}

}
