    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="car_type_id" class="form-label">Тип автомобиля</label>
                <select class="form-select" id="car_type_id" name="car_type_id" required>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('car_type_id', $car->car_type_id ?? '') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Название модели</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ old('name', $car->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">URL-адрес (slug)</label>
                <input type="text" class="form-control" id="slug" name="slug"
                       value="{{ old('slug', $car->slug ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Цена (руб.)</label>
                <input type="number" class="form-control" id="price" name="price"
                       value="{{ old('price', $car->price ?? '') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="engine" class="form-label">Двигатель</label>
                <input type="text" class="form-control" id="engine" name="engine"
                       value="{{ old('engine', $car->engine ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="power" class="form-label">Мощность (л.с.)</label>
                <input type="number" class="form-control" id="power" name="power"
                       value="{{ old('power', $car->power ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="color" class="form-label">Цвет</label>
                <input type="text" class="form-control" id="color" name="color"
                       value="{{ old('color', $car->color ?? '') }}" required>
            </div>

            <div class="mb-3 form-check form-switch">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $car->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Активный в каталоге</label>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Описание</label>
        <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $car->description ?? '') }}</textarea>
    </div>

    <div class="mb-3">
        <label for="main_image" class="form-label">Основное изображение</label>
        <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*">

        @if(isset($car) && $car->image))
        <div class="mt-2">
            <img src="{{ Storage::url($car->image) }}" alt="{{ $car->name }}" style="max-height: 150px;" class="img-thumbnail">
            <input type="hidden" name="existing_main_image" value="{{ $car->image }}">
        </div>
        @endif
    </div>

    <div class="mb-3">
        <label class="form-label">Галерея изображений</label>
        <div id="gallery-container">
            <!-- Существующие изображения -->
            @if(isset($car) && $car->images->count())
                @foreach($car->images as $image)
                    <div class="gallery-item mb-3 border p-2 position-relative">
                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->alt }}" class="img-thumbnail mb-2" style="max-height: 150px;">
                        <input type="text" class="form-control mb-2" name="image_titles[{{ $image->id }}]"
                               value="{{ old('image_titles.'.$image->id, $image->alt) }}" placeholder="Описание изображения">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="delete_image_{{ $image->id }}">
                            <label class="form-check-label" for="delete_image_{{ $image->id }}">Удалить изображение</label>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="gallery-upload mb-3">
                <input type="file" class="form-control mb-2" name="gallery_images[]" accept="image/*">
                <input type="text" class="form-control" name="new_image_titles[]" placeholder="Описание изображения">
            </div>
        </div>

        <button type="button" class="btn btn-sm btn-outline-primary" id="add-gallery-image">
            <i class="bi bi-plus"></i> Добавить изображение
        </button>
    </div>

        <a href="{{ route('cars.index') }}" class="btn btn-secondary">Назад</a>
        <button type="submit" class="btn btn-primary">
            {{ isset($car) ? 'Обновить' : 'Создать' }}
        </button>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Добавление новых полей для изображений галереи
            document.getElementById('add-gallery-image').addEventListener('click', function() {
                const container = document.getElementById('gallery-container');
                const newItem = document.createElement('div');
                newItem.className = 'gallery-upload mb-3';
                newItem.innerHTML = `
            <input type="file" class="form-control mb-2" name="gallery_images[]" accept="image/*">
            <input type="text" class="form-control" name="new_image_titles[]" placeholder="Описание изображения">
            <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-gallery-item">
                <i class="bi bi-trash"></i> Удалить
            </button>
        `;
                container.appendChild(newItem);
            });

            // Удаление полей галереи
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-gallery-item')) {
                    e.target.closest('.gallery-upload').remove();
                }
            });

            // Генерация slug из названия
            document.getElementById('name').addEventListener('blur', function() {
                if (!document.getElementById('slug').value) {
                    const slug = this.value.toLowerCase()
                        .replace(/[^\w\s-]/g, '') // Удаляем спецсимволы
                        .replace(/[\s_-]+/g, '-')  // Заменяем пробелы и подчеркивания на дефисы
                        .replace(/^-+|-+$/g, '');  // Удаляем дефисы в начале и конце
                    document.getElementById('slug').value = slug;
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .gallery-item {
            position: relative;
        }
        .remove-gallery-item {
            position: absolute;
            top: 5px;
            right: 5px;
        }
    </style>
@endpush
