<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\str;
use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\news;


class Blogfront extends Controller
{
    public function showblog()
    {
        $Blogs = Blog::where('status_id', 1)->latest()->with('categories')->get();
        $categories = Blogcategory::withCount('blogs')->get();
        $newss = news::where('status_id', 1)->latest()->get();
        $blog = Blog::where('status_id', 1)->latest()->get();
        $blogcategory = Blogcategory::withCount('blogs')->latest()->get();
        $blogmodify = Blog::where('status_id', 1)->latest('updated_at')->get();
        $newsmodify = news::where('status_id', 1)->latest('updated_at')->get();
        return view('frontend.Blog.blog', compact('Blogs', 'categories','blog','newss','blogcategory','blogmodify','newsmodify'));
    }
    public function blogsbyslug($slug){
        $blog= Blog::with('categories')->whereLike('slug', $slug)->first();
        $related_blogs = Blog::where('status_id', 1)->where('category_id', $blog->categories->id)->get();
        $categories = Blogcategory::withCount('blogs')->get();
        $blogs = Blog::where('status_id', 1)->latest()->get();
        $news = News::where('status_id', 1)->latest()->get();
        $blogcategory = Blogcategory::withCount('blogs')->latest()->get();
        $blogmodify = Blog::where('status_id', 1)->latest('updated_at')->get();
        $newsmodify = news::where('status_id', 1)->latest('updated_at')->get();
        return view('Frontend.Dashboard.single',['blogcategory'=>$blogcategory,'newsmodify'=>$newsmodify,'blogmodify'=>$blogmodify,'blog' => $blog,'related_blogs'=>$related_blogs,'categories'=>$categories,'blogs'=>$blogs,'news'=>$news]);
    }
//     public function blogsbytitle($blogcat)
//     {
//         $categorys = Blogcategory::with('blogs')->where('title', $blogcat)->first();
//         $category=$categorys->blogs->where('status_id',1);
//         if (!$category) {
//             abort(404, 'Category not found');
//         }
    
//         $related_title_blog = $category;
    
//         return view('Frontend.Blog.blogcategory', [
//             'related_title_blog' => $related_title_blog,
//         ]);


    
// }
public function getBlogsByCategory(Request $request)
{
    $categoryId = $request->input('category_id');
    
    if (!$categoryId) {
        return response()->json(['status' => 'error', 'message' => 'Category ID is required']);
    }

    $blogs = Blog::where('category_id', $categoryId)->where('status_id',1)->get();

    return response()->json([
        'status' => 'success',
        'data' => $blogs
    ]);
}

public function loadMoreBlogs(Request $request)
{
    $offset = $request->input('offset', 0);  
    $limit = $request->input('limit', 2);  

    $blogs = Blog::where('status_id', 1)
    ->latest()
    ->skip($offset)
    ->take($limit)
    ->get();

    $count = Blog::where('status_id', 1)->count();
 
    $data = $blogs->map(function ($blog) {
        return [
            'Title' => $blog->title,
            'slug' => $blog->slug,
            'image' => asset($blog->image),  
        ];
    });
    return response()->json([
        'status' => 'success',
        'data' => $data,
        'count'=>$count,
    ]);
}
}