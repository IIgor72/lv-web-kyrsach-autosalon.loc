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
        $request->merge(['is_active' => $request->has('is_active')]);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:cars,slug,'.$car->id,
            'car_type_id' => 'required|exists:car_types,id',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'engine' => 'required|string|max:50',
            'power' => 'required|integer',
            'color' => 'required|string|max:50',
            'is_active' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_titles' => 'nullable|array',
            'image_titles.*' => 'nullable|string|max:255',
            'new_image_titles' => 'nullable|array',
            'new_image_titles.*' => 'nullable|string|max:255',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:car_images,id'
        ]);

        // Обновление основного изображения
        if ($request->hasFile('main_image')) {
            // Удаляем старое изображение если есть
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }

            $path = $request->file('main_image')->store('cars/' . $car->id, 'public');
            $validated['image'] = $path;
        } elseif ($request->has('existing_main_image')) {
            $validated['image'] = $request->existing_main_image;
        }

        $car->update($validated);

/*        // Обновление названий изображений галереи
        if ($request->image_titles) {
            foreach ($request->image_titles as $id => $title) {
                $car->images()->where('id', $id)->update(['alt' => $title]);
            }
        }*/

        // Удаление отмеченных изображений
        if ($request->delete_images) {
            $imagesToDelete = $car->images()->whereIn('id', $request->delete_images)->get();
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

/*        // Добавление новых изображений в галерею
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $key => $file) {
                $path = $file->store('cars/' . $car->id, 'public');

                $car->images()->create([
                    'image_path' => $path,
                    'alt' => $request->new_image_titles[$key] ?? 'Фото автомобиля ' . $car->name
                ]);
            }
        }*/

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
        $photosFromArchive = [];
        if ($request->hasFile('photos_archive')) {
            $zip = new \ZipArchive;
            if ($zip->open($request->file('photos_archive')->getRealPath()) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    if (substr($filename, -1) !== '/') {
                        $photosFromArchive[pathinfo($filename, PATHINFO_FILENAME)] = $zip->getFromIndex($i);
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

            // Создаем автомобиль
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
                'image' => null
            ]);

            // Обработка изображения
            if (isset($data['image'])) {
                $imagePath = null;
                $imageFilename = pathinfo($data['image'], PATHINFO_FILENAME);

                // Пробуем загрузить по URL из CSV
                if (filter_var($data['image'], FILTER_VALIDATE_URL)) {
                    try {
                        $imageContent = file_get_contents($data['image']);
                        if ($imageContent !== false) {
                            $filename = Str::random(20).'.'.pathinfo($data['image'], PATHINFO_EXTENSION);
                            $path = "cars/{$car->id}/{$filename}";
                            Storage::disk('public')->put($path, $imageContent);
                            $imagePath = $path;
                        }
                    } catch (\Exception $e) {

                    }
                }

                // Если URL не сработал, пробуем взять из архива
                if (!$imagePath && isset($photosFromArchive[$imageFilename])) {
                    $photoContent = $photosFromArchive[$imageFilename];
                    $filename = Str::random(20).'.jpg';
                    $path = "cars/{$car->id}/{$filename}";
                    Storage::disk('public')->put($path, $photoContent);
                    $imagePath = $path;
                }

                // Если изображение найдено добавляем в модели
                if ($imagePath) {
                    $car->images()->create([
                        'image_path' => $imagePath,
                        'alt' => $data['name']
                    ]);

                    $car->update(['image' => $imagePath]);
                }
            }
        }

        return redirect()->route('admin.cars.index')
            ->with('success', 'Каталог успешно импортирован');
    }
}
