@extends('layouts.app')

@section('title', 'Добавить новость')

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{--            @include('admin.partials.sidebar')--}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Добавить новость</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Назад к списку
                        </a>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Основная информация</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="title" class="form-label">Заголовок <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="type" class="form-label">Тип новости</label>
                                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                            <option value="">Выберите тип</option>
                                            <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>Новость</option>
                                            <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>Акция</option>
                                            <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>Событие</option>
                                        </select>
                                        @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Изображение</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                               id="image" name="image" accept="image/*">
                                        <div class="form-text">Загрузите изображение для новости (необязательно)</div>
                                        @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="content" class="form-label">Содержание <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('content') is-invalid @enderror"
                                                  id="content" name="content" rows="15" required>{{ old('content') }}</textarea>
                                        @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="published_at" class="form-label">Дата публикации</label>
                                                <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                                       id="published_at" name="published_at" value="{{ old('published_at') }}">
                                                <div class="form-text">Оставьте пустым для автоматической установки текущей даты</div>
                                                @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="form-check form-switch mt-4">
                                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                                        {{ old('is_active', true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_active">
                                                        Активна
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Отмена</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-lg"></i> Создать новость
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Помощь</h6>
                            </div>
                            <div class="card-body">
                                <small class="text-muted">
                                    <strong>Заголовок:</strong> Основной заголовок новости<br><br>
                                    <strong>Тип:</strong> Категория новости (новость, акция, событие)<br><br>
                                    <strong>Изображение:</strong> Основное изображение для новости<br><br>
                                    <strong>Содержание:</strong> Полный текст новости<br><br>
                                    <strong>Дата публикации:</strong> Когда новость будет опубликована. Если не указана, устанавливается автоматически<br><br>
                                    <strong>Активна:</strong> Отображать ли новость на сайте
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
