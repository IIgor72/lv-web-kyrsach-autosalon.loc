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

    public function store(TestDriveRequest $request, Car $car)
{
    if (!$car->is_active) {
        return back()->withInput()->with('error', 'Этот автомобиль временно недоступен для тест-драйва');
    }

    $validated = $request->validated();
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
