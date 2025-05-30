@extends('layouts.app')

@section('title', 'Управление новостями')

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{--            @include('admin.partials.sidebar')--}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Управление новостями</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Добавить новость
                            </a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Заголовок</th>
                            <th>Тип</th>
                            <th>Дата публикации</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($news as $article)
                            <tr>
                                <td>{{ $article->id }}</td>
                                <td>{{ Str::limit($article->title, 50) }}</td>
                                <td>
                                    @switch($article->type)
                                        @case('news')
                                            <span class="badge bg-primary">Новость</span>
                                            @break
                                        @case('promotion')
                                            <span class="badge bg-success">Акция</span>
                                            @break
                                        @case('event')
                                            <span class="badge bg-warning">Событие</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">Не указан</span>
                                    @endswitch
                                </td>
                                <td>{{ $article->published_at ? $article->published_at->format('d.m.Y H:i') : 'Не опубликована' }}</td>
                                <td>
                                <span class="badge bg-{{ $article->is_active ? 'success' : 'secondary' }}">
                                    {{ $article->is_active ? 'Активна' : 'Неактивна' }}
                                </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.news.edit', $article) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $article) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить эту новость?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $news->links() }}
                </div>
            </main>
        </div>
    </div>
@endsection
