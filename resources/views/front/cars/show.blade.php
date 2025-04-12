@extends('layouts.app')

@section('title', $car->name)

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div id="carImages" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('storage/' . $car->image) }}" class="d-block w-100" alt="{{ $car->name }}">
                </div>
                @foreach($car->images as $image)
                <div class="carousel-item">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="{{ $car->name }}">
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carImages" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carImages" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
    <div class="col-md-6">
        <h1>{{ $car->name }}</h1>
        <p class="text-primary fw-bold fs-3">От {{ number_format($car->price, 0, ',', ' ') }} руб.</p>

        <div class="mb-4">
            <h3>Характеристики</h3>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Тип:</strong> {{ $car->name }}</li>
                <li class="list-group-item"><strong>Двигатель:</strong> {{ $car->engine }}</li>
                <li class="list-group-item"><strong>Мощность:</strong> {{ $car->power }}</li>
                <li class="list-group-item"><strong>Цвет:</strong> {{ $car->color }}</li>
            </ul>
        </div>

        <div class="d-grid gap-2 d-md-flex">
            {{-- <a href="{{ route('test-drive.create', $car) }}" class="btn btn-primary btn-lg">Записаться на тест-драйв</a> --}}
            <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary btn-lg">Назад к моделям</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h2>Описание</h2>
        <div class="card">
            <div class="card-body">
                {!! $car->description !!}
            </div>
        </div>
    </div>
</div>
@endsection
