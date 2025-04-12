@extends('layouts.app')

@section('title', 'Главная')

@push('styles')
<style>
    #mainCarousel .carousel-control-prev,
    #mainCarousel .carousel-control-next {
        width: 5%;
        opacity: 1;
    }

    #mainCarousel .carousel-control-prev-icon,
    #mainCarousel .carousel-control-next-icon {
        background-color: rgba(0,0,0,0.5);
        border-radius: 50%;
        padding: 1rem;
    }

    /* Стили для карусели новостей */
    #newsCarousel {
        margin-bottom: 2rem;
    }

    #newsCarousel .carousel-control-prev,
    #newsCarousel .carousel-control-next {
        width: 40px;
        height: 40px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.3);
        border-radius: 50%;
    }

    #newsCarousel .carousel-item {
        padding: 0 15px;
    }

    #newsCarousel .news-card {
        height: 100%;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    #newsCarousel .news-card:hover {
        transform: translateY(-5px);
    }

    #newsCarousel .card-img-top {
        height: 180px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4">Автомобили</h2>
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <!-- Индикаторы слайдов -->
            <div class="carousel-indicators">
                @foreach($cars as $key => $car)
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="{{ $key }}"
                        class="{{ $key === 0 ? 'active' : '' }}" aria-current="true"></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach($cars as $key => $car)
                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                    @if($car->image)
                    <img src="{{ Storage::url($car->image) }}" class="d-block w-100" alt="{{ $car->name }}">
                    @else
                    <img src="{{ asset('images/default-car.jpg') }}" class="d-block w-100" alt="No image">
                    @endif
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $car->name }}</h5>
                        <p>От {{ number_format($car->price, 0, ',', ' ') }} руб.</p>
                        <a href="{{ route('cars.show', $car->slug) }}" class="btn btn-primary">Подробнее</a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Кнопки переключения -->
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4">Новости и акции</h2>

        <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($news->chunk(3) as $key => $newsChunk)
                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                    <div class="row">
                        @foreach($newsChunk as $item)
                        <div class="col-md-4">
                            <div class="card news-card h-100">
                                @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                                @else
                                <img src="{{ asset('images/default-news.jpg') }}" class="card-img-top" alt="No image">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $item->title }}</h5>
                                    <p class="card-text flex-grow-1">{{ Str::limit(strip_tags($item->content), 100) }}</p>
                                    <div class="mt-auto">
                                        <a href="{{ route('news.show', $item->id) }}" class="btn btn-outline-primary">Читать далее</a>
                                    </div>
                                </div>
                                <div class="card-footer text-muted">
                                    {{ $item->published_at->format('d.m.Y') }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Кнопки переключения -->
            <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Подключение FontAwesome для иконок -->
<script src="https://kit.fontawesome.com/ваш-код.js" crossorigin="anonymous"></script>

<!-- Инициализация карусели -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const carsCarousel = new bootstrap.Carousel('#carsCarousel', {
        interval: false, // Отключаем автоматическое перелистывание
        wrap: true       // Включаем зацикливание
    });
});
</script>
