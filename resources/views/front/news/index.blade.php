@extends('layouts.app')

@section('title', 'Новости и акции')

@push('styles')
<style>
    /* Правильное определение размеров favicon */
    link[rel="icon"] {
        width: 16px;
        height: 16px;
    }

    /* Дополнительные стили для улучшения отображения */
    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Новости и акции</h1>

            <div class="row g-4">
                @foreach($news as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-newspaper fa-4x text-muted"></i>
                        </div>
                        @endif

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

            <div class="mt-4">
                {{ $news->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Подключение Font Awesome для иконок -->
<script src="https://kit.fontawesome.com/ваш-код.js" crossorigin="anonymous"></script>
@endpush
