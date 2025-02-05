<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\news;


class Dashboard extends Controller
{
    public function dashboard(Request $request) {
        $users = Blog::where('status_id', 1)->latest()->get();
        $news = News::where('status_id', 1)->latest()->get();
    
        return view('frontend.Dashboard.dashboard', compact('users', 'news'));
    }
    public function contact(Request $request){
        $users = Blog::where('status_id', 1)->latest()->get();
        $news = News::where('status_id', 1)->latest()->get();
    
        return view('frontend.Dashboard.Contact_us', compact('users', 'news'));
    }
    
}

