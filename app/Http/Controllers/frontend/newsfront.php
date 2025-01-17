<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\news;
use App\Models\newscategory;
class Newsfront extends Controller
{
    public function shownews()
    {
        $newsview = news::where('status_id', 1)->latest()->with('categories')->get();
        $categories = newscategory::get();
        return view('frontend.News.news', compact('newsview', 'categories'));
    }
    public function newsbyslug($slug)
    {
        $news = news::with('categories')->whereLike('slug', $slug)->first();
        $related_news = news::where('status_id', 1)->where('category_id', $news->categories->id)->get();

        return view('Frontend.News.particularnews', ['news' => $news, 'related_news' => $related_news]);
    }
    // public function newsbytitle($newscat)
    // {
    //     $categorys = newscategory::with('news')->where('title', $newscat)->first();
    //     $category=$categorys->news->where('status_id',1);

    //     if (!$category) {
    //         abort(404, 'Category not found');
    //     }

    //     $related_title_news = $category;

    //     return view('Frontend.News.newscategory', [
    //         'related_title_news' => $related_title_news,
    //     ]);
    // }
    public function fetchByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');

        if (!$categoryId) {
            return response()->json(['status' => 'error', 'message' => 'Category ID is required.']);
        }

        $news = news::where('category_id', $categoryId)->where('status_id',1)->get();

        if ($news->isEmpty()) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $data = $news->map(function ($item) {
            return [
                'title' => $item->title,
                'slug' => $item->slug,
                'image' => asset($item->news_image),
            ];
        });

        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function loadMoreNews(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 2);

        $news = news::where('status_id', 1)
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get();
        $count = news::where('status_id', 1)->count();

        $data = $news->map(function ($news) {
            return [
                'title' => $news->title,
                'slug' => $news->slug,
                'image' => asset($news->news_image),
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'count' => $count,
        ]);
    }
}
