<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::active()->latest()->paginate(5);
        return view('front.news.index', compact('news'));
    }

    public function show(News $news)
    {
        return view('front.news.show', compact('news'));
    }
}
