@extends('layouts.app')

@section('title', 'Редактировать новость')

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{--            @include('admin.partials.sidebar')--}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Редактировать новость: {{ Str::limit($news->title, 40) }}</h1>
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
                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                                @elseif(auth()->user()->isManager())
                                            <form action="{{ route('manager.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                                @endif
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="title" class="form-label">Заголовок <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title', $news->title) }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="type" class="form-label">Тип новости</label>
                                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                            <option value="">Выберите тип</option>
                                            <option value="news" {{ old('type', $news->type) == 'news' ? 'selected' : '' }}>Новость</option>
                                            <option value="promotion" {{ old('type', $news->type) == 'promotion' ? 'selected' : '' }}>Акция</option>
                                            <option value="event" {{ old('type', $news->type) == 'event' ? 'selected' : '' }}>Событие</option>
                                        </select>
                                        @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Изображение</label>
                                        @if($news->image)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $news->image) }}" alt="Текущее изображение" class="img-thumbnail" style="max-width: 200px;">
                                                <div class="form-text">Текущее изображение</div>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                               id="image" name="image" accept="image/*">
                                        <div class="form-text">Загрузите новое изображение, чтобы заменить текущее (необязательно)</div>
                                        @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="content" class="form-label">Содержание <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('content') is-invalid @enderror"
                                                  id="content" name="content" rows="15" required>{{ old('content', $news->content) }}</textarea>
                                        @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="published_at" class="form-label">Дата публикации</label>
                                                <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                                       id="published_at" name="published_at"
                                                       value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}">
                                                @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="form-check form-switch mt-4">
                                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                                        {{ old('is_active', $news->is_active) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_active">
                                                        Активна
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-outline-primary">Отмена</a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-lg"></i> Сохранить изменения
                                            </button>
                                            </a>
                                        @elseif(auth()->user()->isManager())
                                            <a href="{{ route('manager.news.index') }}" class="btn btn-sm btn-outline-primary">Отмена</a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-lg"></i> Сохранить изменения
                                            </button>
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Информация о новости</h6>
                            </div>
                            <div class="card-body">
                                <small class="text-muted">
                                    <strong>ID:</strong> {{ $news->id }}<br>
                                    <strong>Создана:</strong> {{ $news->created_at->format('d.m.Y H:i') }}<br>
                                    <strong>Обновлена:</strong> {{ $news->updated_at->format('d.m.Y H:i') }}<br><br>
                                    <strong>Текущий статус:</strong>
                                    <span class="badge bg-{{ $news->is_active ? 'success' : 'secondary' }}">
                                        {{ $news->is_active ? 'Активна' : 'Неактивна' }}
                                    </span>
                                </small>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Помощь</h6>
                            </div>
                            <div class="card-body">
                                <small class="text-muted">
                                    <strong>Заголовок:</strong> Основной заголовок новости<br><br>
                                    <strong>Тип:</strong> Категория новости (новость, акция, событие)<br><br>
                                    <strong>Изображение:</strong> Основное изображение для новости<br><br>
                                    <strong>Содержание:</strong> Полный текст новости<br><br>
                                    <strong>Дата публикации:</strong> Когда новость будет опубликована<br><br>
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
