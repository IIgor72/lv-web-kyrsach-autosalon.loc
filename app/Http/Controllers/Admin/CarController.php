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
use Illuminate\Support\Facades\DB;

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
            'slug' => 'required|string|unique:cars,slug,',
            'car_type_id' => 'required|exists:car_types,id',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'engine' => 'required|string|max:50',
            'power' => 'required|integer',
            'color' => 'required|string|max:50',
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
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:car_images,id'
        ]);

        DB::beginTransaction();

        try {
            $duplicateErrors = [];

            // Обработка основного изображения
            if ($request->hasFile('main_image')) {
                $mainImage = $request->file('main_image');
                $mainImageHash = md5_file($mainImage->getRealPath());
                $duplicateImage = $this->findDuplicateImage($car, $mainImageHash);

                if ($duplicateImage) {
                    $duplicateErrors[] = 'Основное изображение совпадает с существующим изображением в галерее';
                } else {

                    if ($car->image) {
                        Storage::disk('public')->delete($car->image);
                        $car->images()->where('image_path', $car->image)->delete();
                    }

                    $path = $mainImage->store('cars/'.$car->id, 'public');
                    $validated['image'] = $path;

                    $car->images()->create([
                        'image_path' => $path,
                        'is_main' => true
                    ]);
                }
            } elseif ($request->has('existing_main_image')) {
                $validated['image'] = $request->existing_main_image;
            }

            // Если есть ошибки дубликатов - откатываем
            if (!empty($duplicateErrors)) {
                DB::rollBack();
                return back()
                    ->withErrors(['duplicate_images' => $duplicateErrors])
                    ->withInput();
            }

            $car->update($validated);

            // Удаление отмеченных изображений
            if ($request->delete_images) {
                $imagesToDelete = $car->images()
                    ->whereIn('id', $request->delete_images)
                    ->where('is_main', false)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }

            // Обработка галереи изображений
            if ($request->hasFile('gallery_images')) {
                $existingHashes = $car->images->pluck('image_path')
                    ->filter()
                    ->mapWithKeys(function ($path) {
                        return [$path => md5_file(Storage::disk('public')->path($path))];
                    })
                    ->toArray();

                foreach ($request->file('gallery_images') as $file) {
                    $fileHash = md5_file($file->getRealPath());

                    if (in_array($fileHash, $existingHashes)) {
                        $duplicateErrors[] = 'Изображение "'.$file->getClientOriginalName().'" уже существует';
                        continue;
                    }

                    $path = $file->store('cars/'.$car->id, 'public');
                    $car->images()->create([
                        'image_path' => $path,
                        'is_main' => false
                    ]);

                    $existingHashes[$path] = $fileHash;
                }
            }

            // Вывод ошибок
            if (!empty($duplicateErrors)) {
                DB::rollBack();
                return back()
                    ->withErrors(['duplicate_images' => $duplicateErrors])
                    ->withInput();
            }

            DB::commit();

            return redirect()->route('admin.cars.index')
                ->with('success', 'Автомобиль успешно обновлен');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Произошла ошибка при обновлении: '.$e->getMessage()])
                ->withInput();
        }
    }

    protected function findDuplicateImage(Car $car, string $fileHash): ?\App\Models\CarImage
    {
        // Проверяем основное изображение
        if ($car->image && Storage::disk('public')->exists($car->image)) {
            $existingHash = md5_file(Storage::disk('public')->path($car->image));
            if ($existingHash === $fileHash) {
                return null; // Не считаем дубликатом самого себя
            }
        }

        // Проверяем галерею изображений
        foreach ($car->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                $existingHash = md5_file(Storage::disk('public')->path($image->image_path));
                if ($existingHash === $fileHash) {
                    return $image;
                }
            }
        }

        return null;
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
