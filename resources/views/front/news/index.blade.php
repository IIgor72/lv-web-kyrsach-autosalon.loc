@extends('layouts.app')

@section('title', 'Новости и акции')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Новости и акции</h1>

                    @auth
                        @if(auth()->user()->isManager())
                            <a href="{{ route('manager.news.index') }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Редактировать новости
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="row g-4">
                    @foreach($news as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm overflow-hidden position-relative">
                                <!-- Контейнер для изображения с фиксированной высотой -->
                                <div class="ratio ratio-16x9 bg-light">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}"
                                             class="img-fluid object-fit-cover"
                                             alt="{{ $item->title }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-newspaper fa-4x text-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $item->title }}</h5>
                                    <p class="card-text flex-grow-1">{{ Str::limit(strip_tags($item->content), 150) }}</p>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-{{ $item->type === 'news' ? 'primary' : 'danger' }}">
                                    {{ $item->type === 'news' ? 'Новость' : 'Акция' }}
                                </span>
                                        <small class="text-muted">{{ $item->published_at->format('d.m.Y') }}</small>
                                    </div>
                                </div>

                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="{{ route('news.show', $item->id) }}" class="btn btn-outline-primary w-100">
                                        Подробнее <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($news->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Новостей пока нет</h4>
                        <p class="text-muted">Новости и акции появятся здесь, когда будут добавлены.</p>

                        @auth
                            @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                                <a href="{{ route('manager.news.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-2"></i>Добавить первую новость
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif

                @if($news->hasPages())
                    <div class="mt-4">
                        {{ $news->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .position-relative .dropdown-menu {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: none;
            min-width: 150px;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')
    <!-- Подключение Font Awesome для иконок -->
    <script src="https://kit.fontawesome.com/ваш-код.js" crossorigin="anonymous"></script>

    <!-- Bootstrap JS для работы dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
