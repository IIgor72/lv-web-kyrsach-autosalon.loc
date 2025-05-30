<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\TestDrive;
use Illuminate\Http\Request;
use App\Http\Requests\TestDriveRequest;

class TestDriveController extends Controller
{
    public function create(Car $car)
    {
        if (!$car->is_active) {
            abort(404, 'Этот автомобиль недоступен для тест-драйва');
        }

        return view('front.test_drive.create', compact('car'));
    }

    public function store(Request $request, Car $car)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'date' => 'required|date|after:today',
            'time' => 'required|string',
            'car_id' => 'required|exists:cars,id'
        ]);

        TestDrive::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ваша заявка на тест-драйв успешно отправлена!'
        ]);
    }
}
