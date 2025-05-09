<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\News;
use App\Models\TestDrive;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'carsCount' => Car::count(),
            'newsCount' => News::count(),
            'testDrivesCount' => TestDrive::count(),
            'usersCount' => User::where('role', '!=', 'user')->count()
        ];

        return view('admin.dashboard', $stats);
    }
}
