@extends('layouts.app')

@section('title', 'Управление новостями')

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{--@include('admin.partials.sidebar')--}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Управление новостями</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg"></i> Добавить новость
                                </a>
                            @elseif(auth()->user()->isManager())
                                <a href="{{ route('manager.news.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg"></i> Добавить новость
                                </a>
                            @endif
                        </div>

                        <!-- Кнопка возврата для менеджеров -->
                        @if(auth()->user()->isManager())
                            <div class="btn-group">
                                <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> К странице новостей
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Заголовок</th>
                            <th>Тип</th>
                            <th>Дата публикации</th>
                            <th>Статус</th>
                            <th width="120">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($news as $article)
                            <tr>
                                <td>{{ $article->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($article->image)
                                            <img src="{{ asset('storage/' . $article->image) }}"
                                                 alt="Изображение"
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="fw-medium">{{ Str::limit($article->title, 50) }}</div>
                                            @if($article->subtitle)
                                                <small class="text-muted">{{ Str::limit($article->subtitle, 40) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @switch($article->type)
                                        @case('news')
                                            <span class="badge bg-primary">
                                                <i class="bi bi-newspaper me-1"></i>Новость
                                            </span>
                                            @break
                                        @case('promotion')
                                            <span class="badge bg-success">
                                                <i class="bi bi-percent me-1"></i>Акция
                                            </span>
                                            @break
                                        @case('event')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-calendar-event me-1"></i>Событие
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-question-circle me-1"></i>Не указан
                                            </span>
                                    @endswitch
                                </td>
                                <td>
                                    @if($article->published_at)
                                        <div>{{ $article->published_at->format('d.m.Y') }}</div>
                                        <small class="text-muted">{{ $article->published_at->format('H:i') }}</small>
                                    @else
                                        <span class="text-muted">Не опубликована</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $article->is_active ? 'success' : 'secondary' }}">
                                        <i class="bi bi-{{ $article->is_active ? 'check-circle' : 'x-circle' }} me-1"></i>
                                        {{ $article->is_active ? 'Активна' : 'Неактивна' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Кнопка просмотра -->
                                        <a href="{{ route('news.show', $article) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Просмотр"
                                           target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Кнопка редактирования -->
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('admin.news.edit', $article) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Редактировать">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @elseif(auth()->user()->isManager())
                                            <a href="{{ route('manager.news.edit', $article) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Редактировать">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif

                                        <!-- Кнопка удаления -->
                                        @if(auth()->user()->isAdmin())
                                            <form action="{{ route('admin.news.destroy', $article) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Удалить"
                                                        onclick="return confirmDelete('{{ $article->title }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-newspaper display-1 d-block mb-3"></i>
                                        <h5>Новостей пока нет</h5>
                                        <p>Добавьте первую новость, чтобы она появилась в списке.</p>
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-lg"></i> Добавить новость
                                            </a>
                                        @elseif(auth()->user()->isManager())
                                            <a href="{{ route('manager.news.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-lg"></i> Добавить новость
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    @if($news->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $news->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

                <!-- Статистика для менеджеров -->
                @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">{{ $news->total() ?? 0 }}</h5>
                                    <p class="card-text">Всего новостей</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-success">
                                        {{ $news->where('is_active', true)->count() }}
                                    </h5>
                                    <p class="card-text">Активных</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-info">
                                        {{ $news->where('type', 'news')->count() }}
                                    </h5>
                                    <p class="card-text">Новостей</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-warning">
                                        {{ $news->where('type', 'promotion')->count() }}
                                    </h5>
                                    <p class="card-text">Акций</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,.025);
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete(title) {
            return confirm(`Вы уверены, что хотите удалить новость "${title}"?\n\nЭто действие нельзя будет отменить.`);
        }

        // Автоматическое закрытие алертов через 5 секунд
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
@endpush
