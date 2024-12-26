<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\news;


class dashboard extends Controller
{
    public function dashboard(Request $request){
        $users = Blog::all();
        $news = news::all(); 
        return view('frontend.dashboard', [
            'users' => $users,
            'news' => $news,
        ]);
        }
}

