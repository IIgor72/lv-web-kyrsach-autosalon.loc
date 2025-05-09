<div class="mb-3">
    <label for="name" class="form-label">Название автомобиля</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $car->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="slug" class="form-label">Slug</label>
    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $car->slug ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="type_id" class="form-label">Тип автомобиля</label>
    <select class="form-select" id="type_id" name="type_id" required>
        @foreach($types as $type)
            <option value="{{ $type->id }}" {{ old('type_id', $car->type_id ?? '') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="price" class="form-label">Цена</label>
    <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $car->price ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Описание</label>
    <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $car->description ?? '') }}</textarea>
</div>

<!-- Блок для загрузки фотографий -->
<div class="mb-3">
    <label class="form-label">Фотографии автомобиля</label>
    <div id="photos-container">
        <!-- Существующие фото (для редактирования) -->
        @if(isset($car) && $car->images->count())
            @foreach($car->images as $image)
                <div class="photo-item mb-3 border p-2">
                    <img src="{{ asset($image->path) }}" alt="{{ $image->alt }}" class="img-thumbnail mb-2" style="max-height: 150px;">
                    <input type="text" class="form-control mb-2" name="photo_titles[{{ $image->id }}]"
                           value="{{ old('photo_titles.'.$image->id, $image->alt) }}" placeholder="Название фото">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="delete_photos[]" value="{{ $image->id }}" id="delete_photo_{{ $image->id }}">
                        <label class="form-check-label" for="delete_photo_{{ $image->id }}">Удалить фото</label>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Новые фото -->
        <div class="photo-upload mb-3">
            <input type="file" class="form-control mb-2" name="photos[]" accept="image/*">
            <input type="text" class="form-control" name="photo_titles_new[]" placeholder="Название для этого фото">
        </div>
    </div>

    <button type="button" class="btn btn-sm btn-outline-primary" id="add-photo-btn">
        <i class="bi bi-plus"></i> Добавить еще фото
    </button>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $car->is_active ?? false) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Активен</label>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Добавление новых полей для фото
            document.getElementById('add-photo-btn').addEventListener('click', function() {
                const container = document.getElementById('photos-container');
                const newPhotoDiv = document.createElement('div');
                newPhotoDiv.className = 'photo-upload mb-3';
                newPhotoDiv.innerHTML = `
                <input type="file" class="form-control mb-2" name="photos[]" accept="image/*">
                <input type="text" class="form-control" name="photo_titles_new[]" placeholder="Название для этого фото">
                <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-photo-btn">
                    <i class="bi bi-trash"></i> Удалить
                </button>
            `;
                container.appendChild(newPhotoDiv);
            });

            // Удаление полей для фото
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-photo-btn')) {
                    e.target.closest('.photo-upload').remove();
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .photo-item {
            position: relative;
        }
        .remove-photo-btn {
            position: absolute;
            top: 5px;
            right: 5px;
        }
    </style>
@endpush
