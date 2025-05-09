<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\News;
use App\Models\TestDrive;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'carsCount' => Car::count(),
            'newsCount' => News::count(),
            'testDrivesCount' => TestDrive::count(),
            'usersCount' => User::count(),
            'activities' => Activity::latest()->take(10)->get(),
            'lastCronRun' => $this->getLastCronRunTime()
        ]);
    }

    protected function getLastCronRunTime()
    {
        // Логика получения времени последнего выполнения CRON
        return now()->subMinutes(rand(0, 60))->diffForHumans();
    }
}
