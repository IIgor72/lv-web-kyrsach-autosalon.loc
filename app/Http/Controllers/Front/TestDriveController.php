<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\TestDrive;
use Illuminate\Http\Request;

class TestDriveController extends Controller
{
    public function create(Car $car)
    {
        return view('front.test_drive.create', compact('car'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'date' => 'required|date|after:today',
            'time' => 'required',
        ]);

        TestDrive::create($validated);

        // Отправка email администратору
        // Mail::to(config('mail.admin_email'))->send(new TestDriveRequested($validated));

        return redirect()->route('cars.index')->with('success', 'Заявка на тест-драйв успешно отправлена!');
    }
}
