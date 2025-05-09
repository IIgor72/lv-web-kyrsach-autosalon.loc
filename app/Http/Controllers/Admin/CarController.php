<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::with('type')->latest()->paginate(10);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        $types = CarType::all();
        return view('admin.cars.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:cars,slug',
            'type_id' => 'required|exists:car_types,id',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'is_active' => 'boolean',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'photo_titles_new' => 'nullable|array',
            'photo_titles_new.*' => 'nullable|string|max:255'
        ]);

        $car = Car::create($validated);

        // Сохранение фотографий
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $key => $photo) {
                $path = $photo->store('cars/' . $car->id, 'public');

                $car->images()->create([
                    'path' => $path, // Сохраняем только путь относительно storage
                    'alt' => $request->photo_titles_new[$key] ?? 'Фото автомобиля ' . $car->name
                ]);
            }
        }

        return redirect()->route('admin.cars.index')
            ->with('success', 'Автомобиль успешно добавлен');
    }

    public function edit(Car $car)
    {
        $types = CarType::all();
        return view('admin.cars.edit', compact('car', 'types'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:cars,slug,'.$car->id,
            'type_id' => 'required|exists:car_types,id',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'is_active' => 'boolean',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'photo_titles' => 'nullable|array',
            'photo_titles.*' => 'nullable|string|max:255',
            'photo_titles_new' => 'nullable|array',
            'photo_titles_new.*' => 'nullable|string|max:255',
            'existing_photos' => 'nullable|array',
            'existing_photos.*' => 'exists:car_images,id'
        ]);

        $car->update($validated);

        // Обновление названий существующих фото
        if ($request->photo_titles) {
            foreach ($request->photo_titles as $id => $title) {
                $car->images()->where('id', $id)->update(['alt' => $title]);
            }
        }

        // Удаление фото, которых нет в списке существующих
        if ($request->existing_photos) {
            $car->images()->whereNotIn('id', $request->existing_photos)->get()->each(function($image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            });
        }

        // Добавление новых фото
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $key => $photo) {
                $path = $photo->store('cars/' . $car->id, 'public');

                $car->images()->create([
                    'path' => $path,
                    'alt' => $request->photo_titles_new[$key] ?? 'Фото автомобиля ' . $car->name
                ]);
            }
        }

        return redirect()->route('admin.cars.index')
            ->with('success', 'Автомобиль успешно обновлен');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('admin.cars.index')
            ->with('success', 'Автомобиль успешно удален');
    }

    public function showImportForm()
    {
        return view('admin.cars.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
            'photos_archive' => 'nullable|file|mimes:zip'
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        // Обработка ZIP архива с фото (если загружен)
        $photos = [];
        if ($request->hasFile('photos_archive')) {
            $zipPath = $request->file('photos_archive')->store('temp');
            $zip = new \ZipArchive;
            if ($zip->open(storage_path('app/'.$zipPath)) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $photos[pathinfo($filename, PATHINFO_FILENAME)] = $filename;
                }
            $zip->close();
        }
            Storage::delete($zipPath);
        }

        if ($extension === 'csv') {
            $this->importFromCSV($file, $photos);
        } else {
            $this->importFromExcel($file, $photos);
        }

        return redirect()->route('admin.cars.index')
            ->with('success', 'Каталог успешно импортирован');
    }

    private function importFromCSV($file, $photos)
    {
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $this->createCarFromRecord($record, $photos);
        }
    }

    private function importFromExcel($file, $photos)
    {
        Excel::import(new class($photos) implements \Maatwebsite\Excel\Concerns\ToCollection {
            protected $photos;

            public function __construct($photos)
            {
                $this->photos = $photos;
            }

            public function collection(\Illuminate\Support\Collection $rows)
            {
                foreach ($rows as $row) {
                    $record = $row->toArray();
                    app(AdminCarController::class)->createCarFromRecord($record, $this->photos);
                }
            }
        }, $file);
    }

    public function createCarFromRecord(array $record, array $photos = [])
    {
        // Основные данные автомобиля
        $car = Car::create([
            'name' => $record['name'] ?? $record['model'] ?? 'Неизвестно',
            'slug' => Str::slug($record['name'] ?? $record['model'] ?? uniqid()),
            'type_id' => $this->getTypeId($record['type'] ?? ''),
            'price' => $record['price'] ?? 0,
            'description' => $record['description'] ?? '',
            'is_active' => true
        ]);

        // Обработка фото
        if (isset($record['photo']) && isset($photos[$record['photo']])) {
            $this->processCarPhoto($car, $photos[$record['photo']], $record['photo_title'] ?? '');
        }
    }

    private function getTypeId($typeName)
    {
        return CarType::firstOrCreate(['name' => $typeName])->id;
    }

    private function processCarPhoto($car, $photoPath, $title)
    {
        $filename = uniqid() . '.' . pathinfo($photoPath, PATHINFO_EXTENSION);
        $storagePath = "cars/{$car->id}/{$filename}";

        Storage::disk('public')->put($storagePath, file_get_contents($photoPath));

        $car->images()->create([
            'path' => $storagePath,
            'alt' => $title ?: 'Фото автомобиля ' . $car->name
        ]);
    }
}
