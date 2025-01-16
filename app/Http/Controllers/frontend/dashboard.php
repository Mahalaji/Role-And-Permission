<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\news;


class Dashboard extends Controller
{
    public function dashboard(Request $request){
        $users = Blog::where('status_id', 1)->latest()->get();
        $news = news::where('status_id', 1)->latest()->get();; 
        return view('frontend.Dashboard.dashboard', [
            'users' => $users,
            'news' => $news,
        ]);
        }
}

