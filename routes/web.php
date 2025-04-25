<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Front\{
    CarController,
    NewsController,
    ServiceController,
    TestDriveController,
    ContactController,
    HomeController
};

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Автомобили
Route::prefix('cars')->group(function () {
    Route::get('/', [CarController::class, 'index'])->name('cars.index');
    Route::get('/{slug}', [CarController::class, 'show'])->name('cars.show');
});

// Новости
Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('/{news}', [NewsController::class, 'show'])->name('news.show');
});

// Сервис
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

// Тест-драйв
//Route::get('/test-drive', [TestDriveController::class, 'index'])->name('test-drive.index');
//Route::post('/test-drive', [TestDriveController::class, 'store'])->name('test-drive.store');

// Контакты
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

Route::get('/welcome', function () {
    return view('welcome');
});



