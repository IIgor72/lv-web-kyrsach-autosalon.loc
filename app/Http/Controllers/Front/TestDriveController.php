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
        if (!$car->is_active) {
            abort(404, 'Этот автомобиль недоступен для тест-драйва');
        }

        return view('front.test_drive.create', compact('car'));
    }

    public function store(Request $request, Car $car)
{
    if (!$car->is_active) {
        return back()->withInput()->with('error', 'Этот автомобиль временно недоступен для тест-драйва');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255|regex:/^[а-яА-ЯёЁ\s]+$/u',
        'phone' => 'required|string|max:20|regex:/^\+?\d{1}?\s?\(?\d{3}\)?\s?\d{3}-?\d{2}-?\d{2}$/',
        'email' => 'required|email|max:255',
        'date' => 'required|date|after:today',
        'time' => 'required',
    ], [
        'name.regex' => 'ФИО должно содержать только русские буквы',
        'phone.regex' => 'Укажите телефон в формате +7 (XXX) XXX-XX-XX',
        'date.after' => 'Дата тест-драйва должна быть не ранее завтрашнего дня',
    ]);

    $validated['car_id'] = $car->id;

    try {
        TestDrive::create($validated);

        return redirect()->route('cars.show', $car->slug)
            ->with('success', 'Ваша заявка на тест-драйв '.$car->name.' успешно отправлена! Наш менеджер свяжется с вами в течение часа.');

    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Произошла ошибка при отправке заявки. Пожалуйста, попробуйте позже.');
    }
}
}
