@extends('layouts.app')

@section('title', 'Модельный ряд')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-4">Модельный ряд</h1>

        @foreach($types as $type)
        <div class="mb-5">
            <h2>{{ $type->name }}</h2>
            <div class="row">
                @foreach($type->cars as $car)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $car->image) }}" class="card-img-top" alt="{{ $car->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $car->name }}</h5>
                            <p class="card-text">{{ Str::limit($car->description, 100) }}</p>
                            <p class="text-primary fw-bold">От {{ number_format($car->price, 0, ',', ' ') }} руб.</p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('cars.show', $car->slug) }}" class="btn btn-primary me-2">Подробнее</a>
                            {{-- <a href="{{ route('test-drive.create', $car) }}" class="btn btn-outline-primary">Тест-драйв</a> --}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
