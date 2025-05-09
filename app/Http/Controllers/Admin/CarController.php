<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'car_type_id' => 'required|exists:car_types,id',
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
            'car_type_id' => 'required|exists:car_types,id',
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
            'file' => 'required|file|mimes:csv,txt',
            'photos_archive' => 'nullable|file|mimes:zip'
        ]);

        // Обработка CSV файла
        $csvData = array_map('str_getcsv', file($request->file('file')->getRealPath()));
        $headers = array_shift($csvData);

        // Обработка ZIP архива с фото
        $photos = [];
        if ($request->hasFile('photos_archive')) {
            $zip = new \ZipArchive;
            if ($zip->open($request->file('photos_archive')->getRealPath()) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    if (substr($filename, -1) !== '/') {
                        $photos[pathinfo($filename, PATHINFO_FILENAME)] = $zip->getFromIndex($i);
                    }
                }
                $zip->close();
            }
        }

        // Импорт данных
        foreach ($csvData as $row) {
            $data = array_combine($headers, $row);

            // Проверяем существование автомобиля
            if (Car::where('slug', $data['slug'])->exists()) {
                continue;
            }

            // Создаем автомобиль (пока без изображения)
            $car = Car::create([
                'car_type_id' => $data['car_type_id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'price' => $data['price'],
                'engine' => $data['engine'],
                'power' => $data['power'],
                'color' => $data['color'],
                'is_active' => $data['is_active'] ?? true,
                'image' => null // Инициализируем как null
            ]);

            // Сохраняем фото если есть
            if (isset($data['image']) && isset($photos[pathinfo($data['image'], PATHINFO_FILENAME)])) {
                $photoContent = $photos[pathinfo($data['image'], PATHINFO_FILENAME)];
                $filename = Str::random(20).'.jpg';
                $path = "cars/{$car->id}/{$filename}";

                Storage::disk('public')->put($path, $photoContent);

                // Создаем запись в CarImage
                $image = $car->images()->create([
                    'image_path' => $path,
                    'alt' => $data['name']
                ]);

                // Обновляем поле image в Car
                $car->update([
                    'image' => $path
                ]);
            }
        }

        return redirect()->route('admin.cars.index')
            ->with('success', 'Каталог успешно импортирован');
    }
}
