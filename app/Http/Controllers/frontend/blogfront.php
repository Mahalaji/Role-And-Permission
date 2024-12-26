<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\str;
use App\Models\Blog;
use App\Models\Blogcategory;


class blogfront extends Controller
{
    public function showblog()
    {
        $Blogs = Blog::with('categories')->get();
        $categories = Blogcategory::withCount('blogs')->get();
        return view('frontend.blogs', compact('Blogs', 'categories'));
    }
    public function blogsbyslug($slug){
        $blog= Blog::with('categories')->whereLike('slug', $slug)->first();
        $related_blogs = Blog::where('blog_title_category', $blog->categories->id)->get();
        return view('Frontend.particularblog',['blog' => $blog,'related_blogs'=>$related_blogs]);
    }
    public function blogsbytitle($blogcat)
    {
        $category = Blogcategory::with('blogs')->where('seo_title', $blogcat)->first();
    
        if (!$category) {
            abort(404, 'Category not found');
        }
    
        $related_title_blog = $category->blogs;
    
        return view('Frontend.blogcategory', [
            'related_title_blog' => $related_title_blog,
        ]);


    
}
public function loadMoreBlogs(Request $request)
{
    $offset = $request->input('offset', 0);  
    $limit = $request->input('limit', 2);  

    $blogs = Blog::skip($offset)->take($limit)->get();  
    $count = Blog::count();
 
    $data = $blogs->map(function ($blog) {
        return [
            'Title' => $blog->Title,
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