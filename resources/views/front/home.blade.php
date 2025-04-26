@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4">Автомобили</h2>
        <div class="position-relative px-5">
            <div id="carsCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($cars->chunk(3) as $key => $carsChunk)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <div class="row g-3">
                            @foreach($carsChunk as $car)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                        @if($car->image)
                                        <img src="{{ Storage::url($car->image) }}" class="img-fluid h-100" alt="{{ $car->name }}" style="object-fit: contain;">
                                        @else
                                        <img src="{{ asset('images/default-car.jpg') }}" class="img-fluid h-100" alt="No image" style="object-fit: contain;">
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-dark">{{ $car->name }}</h5>
                                        <p class="card-text text-dark">От {{ number_format($car->price, 0, ',', ' ') }} руб.</p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="{{ route('cars.show', $car->slug) }}" class="btn btn-primary">Подробнее</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev position-absolute start-0 top-50 translate-middle-y bg-dark rounded-circle" type="button" data-bs-target="#carsCarousel" data-bs-slide="prev" style="width: 40px; height: 40px;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next position-absolute end-0 top-50 translate-middle-y bg-dark rounded-circle" type="button" data-bs-target="#carsCarousel" data-bs-slide="next" style="width: 40px; height: 40px;">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4">Новости и акции</h2>
        <div class="position-relative px-5">
            <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($news->chunk(3) as $key => $newsChunk)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <div class="row g-3">
                            @foreach($newsChunk as $item)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                        @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}" class="img-fluid h-100" alt="{{ $item->title }}" style="object-fit: contain;">
                                        @else
                                        <img src="{{ asset('images/default-news.jpg') }}" class="img-fluid h-100" alt="No image" style="object-fit: contain;">
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-dark">{{ $item->title }}</h5>
                                        <p class="card-text text-dark">{{ Str::limit(strip_tags($item->content), 100) }}</p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="{{ route('news.show', $item->id) }}" class="btn btn-outline-primary">Читать далее</a>
                                        <small class="text-muted">{{ $item->published_at->format('d.m.Y') }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev position-absolute start-0 top-50 translate-middle-y bg-dark rounded-circle" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev" style="width: 40px; height: 40px;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next position-absolute end-0 top-50 translate-middle-y bg-dark rounded-circle" type="button" data-bs-target="#newsCarousel" data-bs-slide="next" style="width: 40px; height: 40px;">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const carsCarousel = new bootstrap.Carousel(document.getElementById('carsCarousel'), {
        interval: 10000,
        wrap: true
    });

    const newsCarousel = new bootstrap.Carousel(document.getElementById('newsCarousel'), {
        interval: 10000,
        wrap: true
    });
});
</script>
@endpush
