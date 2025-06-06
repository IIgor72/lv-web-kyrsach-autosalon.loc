
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
use App\Http\Controllers\Admin\{
    AdminController,
    CarController as AdminCarController,
    NewsController as AdminNewsController,
    TestDriveController as AdminTestDriveController,
    ContactController as AdminContactController,
    UserController
};
use App\Http\Controllers\Auth\LoginController;

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
Route::get('/test-drive/{car}', [TestDriveController::class, 'create'])->name('test-drive.create');
Route::post('/test-drive/{car}', [TestDriveController::class, 'store'])->name('test-drive.store');

// Контакты
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

Route::get('/welcome', function () {
    return view('welcome');
});

// Аутентификация
Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('auth.login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Админка (только для админов)
Route::prefix('admin')->middleware(['auth:sanctum', 'verified','role:admin'])->name('admin.')->group(function () {
    // Главная админ-панель
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Управление автомобилями
    Route::prefix('cars')->controller(AdminCarController::class)->group(function () {
        Route::get('/', 'index')->name('cars.index');
        Route::get('/create', 'create')->name('cars.create');
        Route::post('/', 'store')->name('cars.store');
        Route::get('/{car}/edit', 'edit')->name('cars.edit');
        Route::put('/{car}', 'update')->name('cars.update');
        Route::delete('/{car}', 'destroy')->name('cars.destroy');
        Route::post('/import', [AdminCarController::class, 'import'])->name('cars.import');
        Route::get('/import', [AdminCarController::class, 'showImportForm'])->name('cars.import.form');
    });

    // Управление новостями (для админов)
    Route::prefix('news')->controller(AdminNewsController::class)->group(function () {
        Route::get('/', 'index')->name('news.index');
        Route::get('/create', 'create')->name('news.create');
        Route::post('/', 'store')->name('news.store');
        Route::get('/{news}/edit', 'edit')->name('news.edit');
        Route::put('/{news}', 'update')->name('news.update');
        Route::delete('/{news}', 'destroy')->name('news.destroy');
    });

    // Управление тест-драйвами
    Route::prefix('test-drives')->controller(AdminTestDriveController::class)->group(function () {
        Route::get('/', 'index')->name('test-drives.index');
        Route::get('/{testDrive}', 'show')->name('test-drives.show');
        Route::delete('/{testDrive}', 'destroy')->name('test-drives.destroy');
    });

    // Контакт
    Route::prefix('contacts')->group(function () {
        Route::get('/', [AdminContactController::class, 'index'])->name('admin.contacts.index');
        Route::put('/{contact}', [AdminContactController::class, 'update'])->name('admin.contacts.update');
    });

    Route::post('/cars/import', [AdminCarController::class, 'import'])->name('admin.cars.import');
    Route::get('/cars/import', [AdminCarController::class, 'showImportForm'])->name('admin.cars.import.form');

    // Управление пользователями
    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('users.index');
        Route::get('/create', 'create')->name('users.create');
        Route::post('/', 'store')->name('users.store');
        Route::get('/{user}/edit', 'edit')->name('users.edit');
        Route::put('/{user}', 'update')->name('users.update');
        Route::delete('/{user}', 'destroy')->name('users.destroy');
    });
});

// Отдельные маршруты для менеджера новостей
Route::prefix('manager/news')->middleware(['auth:sanctum', 'verified', 'role:manager'])->name('manager.')->group(function () {
    Route::get('/', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/{news}', [AdminNewsController::class, 'update'])->name('news.update');
});
