<div class="row">

    @if($errors->has('duplicate_images'))
        <div class="alert alert-danger">
            <h6>Обнаружены дубликаты:</h6>
            <ul class="mb-0">
                @foreach($errors->get('duplicate_images') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

<!-- Основное изображение -->
<div class="mb-3">
    <label for="main_image" class="form-label">Основное изображение</label>
    <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*">

    @if(isset($car) && $car->image)
        <div class="mt-2">
            <img src="{{ Storage::url($car->image) }}" alt="{{ $car->name }}" style="max-height: 300px;" class="img-thumbnail">
            <input type="hidden" name="existing_main_image" value="{{ $car->image }}">
        </div>
    @endif
</div>

<!-- Галерея изображений с каруселью -->
<div class="mb-3">
    <label class="form-label">Галерея изображений</label>

    <!-- Карусель существующих изображений -->
    @if(isset($car) && $car->images()->where('is_main', false)->count())
        <div class="gallery-carousel mb-3">
            <div class="d-flex overflow-auto pb-2" style="scrollbar-width: thin;">
                <!-- Основное изображение (первое в галерее) -->
                @if($car->image)
                    <div class="gallery-item me-3 position-relative" style="min-width: 200px;">
                        <img src="{{ Storage::url($car->image) }}" class="img-thumbnail" style="height: 150px; width: 100%; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 p-1 bg-white rounded">
                            <span class="badge bg-primary">Основное</span>
                        </div>
                    </div>
                @endif

                @foreach($car->images()->where('is_main', false)->get() as $image)
                    <div class="gallery-item me-3 position-relative" style="min-width: 200px;">
                        <img src="{{ Storage::url($image->image_path) }}" class="img-thumbnail" style="height: 150px; width: 100%; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 p-1 bg-white rounded d-flex flex-column">
                            <input type="checkbox" class="form-check-input delete-checkbox" name="delete_images[]"
                                   value="{{ $image->id }}" id="delete_image_{{ $image->id }}">
                            <label class="form-check-label visually-hidden" for="delete_image_{{ $image->id }}">Удалить</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn btn-sm btn-danger" id="delete-selected-images">
                    <i class="bi bi-trash"></i> Удалить выбранные
                </button>
{{--                <button type="button" class="btn btn-sm btn-primary" id="set-as-main-image">
                    <i class="bi bi-star-fill"></i> Сделать основным
                </button>--}}
            </div>
        </div>
    @endif

    <!-- Поля для загрузки новых изображений -->
    <div id="gallery-uploads" class="mb-3">
        <div class="input-group mb-2">
            <input type="file" class="form-control" name="gallery_images[]" accept="image/*">
            <button type="button" class="btn btn-outline-danger remove-gallery-item">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    <button type="button" class="btn btn-sm btn-outline-primary" id="add-gallery-image">
        <i class="bi bi-plus"></i> Добавить поле для загрузки
    </button>
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">Назад</a>
    <button type="submit" class="btn btn-primary">
        {{ isset($car) ? 'Обновить' : 'Создать' }}
    </button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Добавление новых полей для загрузки изображений
            const addButton = document.getElementById('add-gallery-image');
            if (addButton) {
                addButton.addEventListener('click', function() {
                    const container = document.getElementById('gallery-uploads');
                    const newItem = document.createElement('div');
                    newItem.className = 'input-group mb-2';
                    newItem.innerHTML = `
                        <input type="file" class="form-control" name="gallery_images[]" accept="image/*">
                        <button type="button" class="btn btn-outline-danger remove-gallery-item">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    container.appendChild(newItem);
                });
            }

            // Удаление выбранных изображений галереи
            const deleteSelectedBtn = document.getElementById('delete-selected-images');
            if (deleteSelectedBtn) {
                deleteSelectedBtn.addEventListener('click', function() {
                    const checkboxes = document.querySelectorAll('.delete-checkbox:checked');
                    if (checkboxes.length === 0) {
                        alert('Пожалуйста, выберите изображения для удаления');
                        return;
                    }

                    if (confirm('Вы уверены, что хотите удалить выбранные изображения?')) {
                        checkboxes.forEach(checkbox => {
                            const item = checkbox.closest('.gallery-item');
                            if (item) {
                                item.style.opacity = '0.5';
                                item.style.pointerEvents = 'none';
                            }
                        });
                    }
                });
            }

            // Установка выбранного изображения как основного
            const setAsMainBtn = document.getElementById('set-as-main-image');
            if (setAsMainBtn) {
                setAsMainBtn.addEventListener('click', function() {
                    const checkboxes = document.querySelectorAll('.gallery-checkbox:checked');
                    if (checkboxes.length !== 1) {
                        alert('Пожалуйста, выберите ровно одно изображение из галереи');
                        return;
                    }

                    const checkbox = checkboxes[0];
                    const imageId = checkbox.value;
                    const galleryItem = checkbox.closest('.gallery-item');

                    if (confirm('Вы уверены, что хотите сделать это изображение основным?')) {
                        // Обновляем UI
                        document.querySelectorAll('.gallery-item .badge').forEach(badge => {
                            badge.classList.remove('bg-primary');
                            badge.classList.add('bg-secondary');
                            badge.textContent = 'Дополнительное';
                        });

                        const badge = galleryItem.querySelector('.badge');
                        badge.classList.remove('bg-secondary');
                        badge.classList.add('bg-primary');
                        badge.textContent = 'Основное (будет сохранено)';

                        // Устанавливаем скрытое поле для серверной обработки
                        let mainImageInput = document.querySelector('input[name="new_main_image_id"]');
                        if (!mainImageInput) {
                            mainImageInput = document.createElement('input');
                            mainImageInput.type = 'hidden';
                            mainImageInput.name = 'new_main_image_id';
                            document.querySelector('form').appendChild(mainImageInput);
                        }
                        mainImageInput.value = imageId;
                    }
                });
            }

            // Удаление полей загрузки (делегирование событий)
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-gallery-item')) {
                    const item = e.target.closest('.input-group');
                    if (item) {
                        item.remove();
                    }
                }
            });

            // Генерация slug из названия
            const nameInput = document.getElementById('name');
            if (nameInput) {
                nameInput.addEventListener('blur', function() {
                    const slugInput = document.getElementById('slug');
                    if (slugInput && !slugInput.value) {
                        const slug = this.value.toLowerCase()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                        slugInput.value = slug;
                    }
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .gallery-carousel {
            position: relative;
            padding: 5px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .gallery-carousel .d-flex {
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding: 5px;
        }
        .gallery-item {
            scroll-snap-align: start;
            position: relative;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 3px;
            background: white;
            transition: opacity 0.3s ease;
        }
        .gallery-item img {
            cursor: pointer;
            transition: transform 0.2s;
            border-radius: 3px;
        }
        .gallery-item img:hover {
            transform: scale(1.03);
        }
        .remove-gallery-item {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
    </style>
@endpush
