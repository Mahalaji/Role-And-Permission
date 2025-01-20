<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blogcategory;
use App\Models\domains;
use App\Models\languages;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Blog;
use App\Models\statuss;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\str;
use Illuminate\Support\Facades\Auth;
use App\Models\Countrylists;

class Blogs extends Controller
{
  
   public function blogshow(){
    return view('Backend.blog.index');
   }
    public function title()
    {
        $titles = Blogcategory::select('title', 'id')->get();
        $domain = domains::select('domainname', 'id')->get();
        $language = languages::select('languagename', 'id')->get();
        $country = Countrylists::select('name', 'id')->get();


        return view('Backend.blog.create', compact('titles', 'domain', 'language','country'));
    }
    function addblog(Request $request)
    {

        $userid = Auth::user();
        $request->validate([
            'title' => 'required',
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'required',
            'seo_title' => 'required',
            'meta_keyword' => 'required',
            'seo_robat' => 'required',
            'meta_description' => 'required',
            'language' => 'required',
            'domain' => 'required',
            'countryname' => 'required',



        ]);
        $Blogadd = new Blog();
        $Blogadd->title = $request->title;
        $Blogadd->name = $request->name;
        $Blogadd->category_id = $request->category_id;
        $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->description);
        $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
        $Blogadd->description = $cleanDescription;
        $Blogadd->seo_title = $request->seo_title;
        $Blogadd->meta_keyword = $request->meta_keyword;
        $Blogadd->seo_robat = $request->seo_robat;
        $Blogadd->meta_description = $request->meta_description;
        $Blogadd->user_id = $userid->id;
        $Blogadd->language_id = $request->language;
        $Blogadd->domain_id = $request->domain;
        $Blogadd->country_id = $request->countryname;



        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'file|max:102400', 
            ]);
        
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
            $query = Blog::select('id', 'title', 'category_id', 'domain_id', 'language_id', 'status_id', 'created_at','username')
                ->with('categories', 'domain', 'language', 'status');

            if (!($user->hasRole(['Admin', 'Blog_Team']))) {
                $query->where('user_id', $user->id);
            }

            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }

            $statuses = statuss::all()->pluck('status', 'id');

            $designationStatusMapping = [
                4 => 2,
                3 => 3,
                2 => 4,
                1 => 5,
            ];

            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/blog/edit/' . $row->id . '" class="btn btn-sm btn-primary" style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('s.no', function ($row) {
                    static $serialNumber = 1; 
                    return $serialNumber++; 
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
                ->addColumn('status_dropdown', function ($row) use ($statuses, $user, $designationStatusMapping) {
                    $name=$row->username;
                    $designationId = $user->designation_id;
                    $id = $row->status_id;
                    
                    if ($designationId < 4) {
                        if ($designationId <= $id || $user->hasRole(['Admin'])) {
                            $options = '';

                            if ($user->hasRole(['Admin'])) {
                                foreach ($statuses as $id => $status) {
                                    $selected = $row->status_id == $id ? 'selected' : '';
                                    $options .= "<option value='$id' $selected>$status</option>";
                                }
                                return "<select class='form-control status-dropdown' data-id='$row->id'>$options</select>";
                            } else {
                                if (isset($designationStatusMapping[$designationId])) {
                                    $visibleCount = $designationStatusMapping[$designationId];
                                    $visibleStatuses = $statuses->slice(-$visibleCount)->all();

                                    foreach ($visibleStatuses as $id => $status) {
                                        $selected = $row->status_id == $id ? 'selected' : '';
                                        $options .= "<option value='$id' $selected>$status</option>";
                                    }

                                    return "<select class='form-control status-dropdown' data-id='$row->id'>$options</select>";
                                } else {
                                    return $statuses[$row->status_id] ?? 'Unknown';
                                }
                       
                            }
                        } else {
                            return "verify by $name" ;
                        }
                    } else {
                        return $statuses[$row->status_id] ?? 'Unknown';
                    }
                })

                ->rawColumns(['edit', 'delete', 'time_ago', 'time_update_ago', 'status_dropdown','s.no'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function updateBlogStatus(Request $request)
    {
        try {
            $blog = Blog::findOrFail($request->blog_id);
            $user = Auth::user();
            $blog->status_id = $request->status_id;
            $blog->username = $user->name;
            $blog->save();

            return response()->json(['message' => 'Status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function edit($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return redirect()->back()->with('error', 'User not found');
        }

        if (!is_object($blog)) {
            return redirect()->back()->with('error', 'Invalid user data');
        }

        $titles = Blogcategory::select('title', 'id')->get();
        $domain = domains::select('domainname', 'id')->get();
        $language = languages::select('languagename', 'id')->get();
        $country = Countrylists::select('name', 'id')->get();

        return view('Backend.blog.edit', compact('blog', 'titles', 'domain', 'language','country'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'required',
            'seo_title' => 'required',
            'meta_keyword' => 'required',
            'seo_robat' => 'required',
            'meta_description' => 'required',
            'language' => 'required',
            'domain' => 'required',
            'countryname' => 'required',

        ]);

        $Blogedit = Blog::find($request->id);

        if (!$Blogedit) {
            return redirect()->back()->withErrors(['error' => 'Blog not found']);
        }

        $Blogedit->title = $request->title;
        $Blogedit->name = $request->name;
        $Blogedit->language_id = $request->language;
        $Blogedit->domain_id = $request->domain;
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
        $Blogedit->status_id = 5;
        $Blogedit->country_id = $request->countryname;


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
    public function destory($id)
    {
        $blogs = Blog::find($id);

        if (!$blogs) {
            return redirect()->back()->withErrors(['error' => 'User not found']);
        }

        $blogs->delete();

        return redirect('/blog')->with('success', 'User deleted successfully');
    }
    public function getBlogsCategoryAjax(Request $request)
    {
        try {
            $query = Blogcategory::select('id', 'title')->withCount('blogs');
            return DataTables::of($query)
            ->addColumn('s.no', function ($row) {
                static $serialNumber = 1; 
                return $serialNumber++; 
            })
                ->addColumn('edit', function ($row) {
                    return '<a href="/blogcategory/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorycategory/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                            ' . csrf_field() . '
                            <button type="submit" class="btn btn-sm btn-danger"style="border: none; outline: none;"><i class="fas fa-trash"></i></button>
                        </form>';
                })
                ->rawColumns(['edit', 'delete','s.no'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function addcategery(Request $request)
    {
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
    public function destorycategory($id)
    {
        $blogcategory = Blogcategory::find($id);

        if (!$blogcategory) {
            return redirect()->back()->withErrors(['error' => 'User not found']);
        }

        $blogcategory->delete();

        return redirect('/blogcategory')->with('success', 'User deleted successfully');
    }
    public function editcategory($id)
    {
        $blogcategory = Blogcategory::find($id);

        if (!$blogcategory) {
            return redirect()->back()->with('error', 'User not found');
        }

        if (!is_object($blogcategory)) {
            return redirect()->back()->with('error', 'Invalid user data');
        }

        return view('Backend.blog.category_edit', compact('blogcategory'));
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
