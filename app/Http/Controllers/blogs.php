<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blogcategory;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\str;
use Illuminate\Support\Facades\Auth;

class blogs extends Controller
{
    // public function bloglistshow(){
    //     $Blogs =Blog::with('categories')->get();
    //     // dd($Blogs);
    //     return view('blognew',['Blogs'=>$Blogs]);
    // }

    public function seo_title()
    {
        $titles = Blogcategory::select('seo_title','id')->get();
        return view('blogadd', compact('titles'));
    }
        function addblog(Request $request) {
        $request->validate([
            'Title' => 'required',
            'Name' => 'required',
            'blog_title_category' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'post_Date' => 'required|date',
            'Description' => 'required',
        ]);
    
        $Blogadd = new Blog();
        $Blogadd->Title = $request->Title;
        $Blogadd->Name = $request->Name;
        $Blogadd->blog_title_category = $request->blog_title_category;
        $Blogadd->post_Date = $request->post_Date;
        $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->Description); 
    $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
    $Blogadd->Description = $cleanDescription;
        $Blogadd->Create_Date = now();
        $Blogadd->Update_Date = now();
        $Blogadd->recycle =1;


    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'images/' . $imageName;
            $image->move(public_path('images'), $imageName);
            $Blogadd->image = $imagePath;
        }
    
        $slug = Str::slug($request->Title);
        $existingSlugCount = Blog::where('slug', $slug)->count();
        if ($existingSlugCount > 0) {
            $slug = $slug . '-' . time();
        }
        $Blogadd->slug = $slug;
    
        $Blogadd->save();
    
        if ($Blogadd) {
            return redirect('/bloglist')->with('success', 'Blog added successfully!');
        } else {
            return back()->with('error', 'Failed to add the blog.');
        }
    }
    public function getBlogsAjax(Request $request)
    {
        try {
            $query = Blog::select('id', 'Name', 'Title', 'blog_title_category', 'Description', 'Create_Date', 'Update_Date', 'post_Date')->with('categories');
    
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('post_Date', [$startDate, $endDate]);
                }
            }
    
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destory/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-sm btn-danger" style="border: none; outline: none;"><i class="fas fa-trash"></i></button>
                            </form>';
                })
                ->addColumn('time_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->Create_Date)->diffForHumans();
                })
                ->addColumn('time_update_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->Update_Date)->diffForHumans();
                })
                ->addColumn('time_post_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->post_Date)->diffForHumans();
                })
                ->rawColumns(['edit', 'delete','time_ago','time_update_ago','time_post_ago'])
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
    
        $titles = Blogcategory::select('seo_title','id')->get();

        return view('blogedit', compact('blog', 'titles'));
    }
    public function update(Request $request)
{
    $request->validate([
        'Title' => 'required',
        'Name' => 'required',
        'blog_title_category' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'post_Date' => 'required|date',
        'Description' => 'required',
    ]);

    $Blogadd = Blog::find($request->id);

    if (!$Blogadd) {
        return redirect()->back()->withErrors(['error' => 'Blog not found']);
    }

    $Blogadd->Title = $request->Title;
    $Blogadd->Name = $request->Name;
    $Blogadd->blog_title_category = $request->blog_title_category;
    $Blogadd->post_Date = $request->post_Date;
    $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->Description); // Remove <p> and <strong> tags
    $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription); // Replace &nbsp; with a space and &#39; with a single quote
    $Blogadd->Description = $cleanDescription;
    
    $Blogadd->Update_Date = now();

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('images'), $imageName);
        $Blogadd->image = 'images/' . $imageName;
    }

    if ($Blogadd->Title !== $request->Title) {
        $slug = Str::slug($request->Title);
        $existingSlugCount = Blog::where('slug', $slug)->where('id', '!=', $request->id)->count();

        if ($existingSlugCount > 0) {
            $slug = $slug . '-' . time();
        }

        $Blogadd->slug = $slug;
    }

    $Blogadd->save();

    return redirect('/bloglist')->with('success', 'Blog updated successfully');
}
public function destory($id){
    $blogs = Blog::find($id); 

    if (!$blogs) {
        return redirect()->back()->withErrors(['error' => 'User not found']);
    }

    $blogs->delete();

    return redirect('/bloglist')->with('success', 'User deleted successfully');
}
public function getBlogsCategoryAjax(Request $request){
    try {
        $query = Blogcategory::select('id', 'seo_title', 'meta_keyword', 'seo_robat', 'meta_description')->withCount('blogs');
        return DataTables::of($query)
            ->addColumn('edit', function ($row) {
                return '<a href="/editcategory/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
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
        'seo_title' => 'required',
        'meta_keyword' => 'required',
        'seo_robat' => 'required',
        'meta_description' => 'required',
    ]);

    $categeryadd = new Blogcategory();
    $categeryadd->seo_title = $request->seo_title;
    $categeryadd->meta_keyword = $request->meta_keyword;
    $categeryadd->seo_robat = $request->seo_robat;
    $categeryadd->meta_description = $request->meta_description;

    $categeryadd->save();

    if ($categeryadd) {
        return redirect('/blogcategorylist')->with('success', 'Category added successfully!');
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

    return redirect('/blogcategorylist')->with('success', 'User deleted successfully');
}
public function editcategory($id){
    $blogcategory = Blogcategory::find($id); 

    if (!$blogcategory) {
        return redirect()->back()->with('error', 'User not found');
    }

    if (!is_object($blogcategory)) {
        return redirect()->back()->with('error', 'Invalid user data');
    }

    return view('blogcategeryedit', compact('blogcategory'));
}
public function updateCategory(Request $request)
{
    $request->validate([
        'seo_title' => 'required',
        'meta_keyword' => 'required',
        'seo_robat' => 'required',
        'meta_description' => 'required',
    ]);

    $categoryupdate = Blogcategory::find($request->id);

    if (!$categoryupdate) {
        return redirect()->back()->withErrors(['error' => 'Blog category not found']);
    }

    $categoryupdate->seo_title = $request->seo_title;
    $categoryupdate->meta_keyword = $request->meta_keyword;
    $categoryupdate->seo_robat = $request->seo_robat;
    $categoryupdate->meta_description = $request->meta_description;

    $categoryupdate->save();

    return redirect('/blogcategorylist')->with('success', 'Blog category updated successfully');
}

}
