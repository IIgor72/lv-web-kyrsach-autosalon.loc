<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        $news = News::active()->latest()->take(3)->get();
        $cars = Car::active()->with('type')->latest()->take(6)->get();
        return view('front.home', compact('news', 'cars'));
    }
}
