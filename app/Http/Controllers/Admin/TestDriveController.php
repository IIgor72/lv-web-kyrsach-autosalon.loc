<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use Illuminate\Http\Request;

class TestDriveController extends Controller
{
    public function index()
    {
        $testDrives = TestDrive::with('car')->latest()->paginate(10);
        return view('admin.test-drives.index', compact('testDrives'));
    }

    public function show(TestDrive $testDrive)
    {
        return view('admin.test-drives.show', compact('testDrive'));
    }

    public function destroy(TestDrive $testDrive)
    {
        $testDrive->delete();
        return redirect()->route('admin.test-drives.index')
            ->with('success', 'Заявка на тест-драйв успешно удалена');
    }
}
