<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\news;
use App\Models\Blogcategory;
use App\Models\newscategory;
class Dashboard extends Controller
{
    public function dashboard(Request $request) {
        $users = Blog::where('status_id', 1)->latest()->get();
        $news = news::where('status_id', 1)->latest()->get();
        $blogcategory = Blogcategory::withCount('blogs')->latest()->get();
        $categories = newscategory::latest()->get();
        $blogmodify = Blog::where('status_id', 1)->latest('updated_at')->get();
        $newsmodify = news::where('status_id', 1)->latest('updated_at')->get();
        return view('frontend.Dashboard.dashboard', compact('users', 'news','categories','blogcategory','blogmodify','newsmodify'));
    }
    public function contact(Request $request){
        $users = Blog::where('status_id', 1)->latest()->get();
        $news = News::where('status_id', 1)->latest()->get();
        $blogcategory = Blogcategory::withCount('blogs')->latest()->get();
        $categories = newscategory::latest()->get();
        $blogmodify = Blog::where('status_id', 1)->latest('updated_at')->get();
        $newsmodify = news::where('status_id', 1)->latest('updated_at')->get();
        return view('frontend.Dashboard.Contact_us', compact('users', 'news','blogcategory','categories','blogmodify','newsmodify'));
    }
    
}

