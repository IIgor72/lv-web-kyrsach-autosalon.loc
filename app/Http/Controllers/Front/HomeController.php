<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        // $cars = Car::all();
        $cars = Car::active()->with('type')->latest()->take(6)->get();
        $news = News::active()->latest()->take(6)->get();

        return view('front.home', compact('news', 'cars'));
    }
}
