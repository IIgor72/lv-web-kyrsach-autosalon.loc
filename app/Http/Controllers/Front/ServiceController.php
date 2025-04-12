<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Page::where('type', 'service')->get();
        return view('front.services.index', compact('services'));
    }
}
