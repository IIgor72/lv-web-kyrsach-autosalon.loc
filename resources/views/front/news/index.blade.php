@extends('layouts.app')

@section('title', 'Новости и акции')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Новости и акции</h1>

            <div class="row g-4">
                @foreach($news as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm overflow-hidden">
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
