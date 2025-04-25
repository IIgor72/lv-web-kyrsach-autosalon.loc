<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarType;

class CarController extends Controller
{
    public function index()
    {
        $types = CarType::with('cars')->get();
        return view('front.cars.index', compact('types'));
    }

    public function show($slug)
    {
        $car = Car::with('images')->where('slug', $slug)->firstOrFail();
        return view('front.cars.show', compact('car'));
    }
}
