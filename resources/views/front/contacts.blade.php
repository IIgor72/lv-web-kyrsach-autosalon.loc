@extends('layouts.app')

@section('title', 'Контакты')

@section('content')
<div class="corporate-container">
    <div class="row mt-4">
        <div class="col-md-6">
            <h1 class="corporate-h1 mb-4">Контакты</h1>

            <div class="corporate-card">
                <div class="corporate-card-header">
                    <h2 class="h4 mb-0">Контактная информация</h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h3 class="corporate-h2 h5">Адрес</h3>
                        <p class="mb-0"><i class="bi bi-geo-alt-fill text-danger me-2"></i>{{ $contact->address }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="corporate-h2 h5">Телефоны</h3>
                        <p class="mb-0"><i class="bi bi-telephone-fill text-danger me-2"></i>{{ $contact->phone }}</p>
                        @if ($contact->phone2 ?? false)
                            <p class="mb-0"><i class="bi bi-telephone-fill text-danger me-2"></i>{{ $contact->phone2 }}</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h3 class="corporate-h2 h5">Email</h3>
                        <p class="mb-0"><i class="bi bi-envelope-fill text-danger me-2"></i>{{ $contact->email }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="corporate-h2 h5">Часы работы</h3>
                        @php
                            $workHours = explode(',', $contact->work_hours ?? '');
                            $weekdays = $workHours[0] ?? 'Пн-Пт: 9:00 - 20:00';
                            $weekends = $workHours[1] ?? 'Сб-Вс: 10:00 - 18:00';
                        @endphp
                        <p class="mb-0"><i class="bi bi-clock-fill text-danger me-2"></i>{{ $weekdays }}</p>
                        <p class="mb-0"><i class="bi bi-clock-fill text-danger me-2"></i>{{ $weekends }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="corporate-card">
                <div class="corporate-card-header">
                    <h2 class="h4 mb-0">Схема проезда</h2>
                </div>
                <div class="card-body p-0">
                    <!-- Яндекс.Карта -->
                    <iframe
                        src="{{ $contact->map_link }}"
                        width="100%"
                        height="400"
                        frameborder="0"
                        style="border:0;"
                        allowfullscreen
                        aria-hidden="false"
                        tabindex="0">
                    </iframe>
                </div>
                <div class="card-footer bg-white">
                    <a href="https://yandex.ru/maps/-/CHfnBB37" target="_blank" class="btn btn-primary">Построить маршрут</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
    .corporate-container {
        padding: 20px;
    }
    .corporate-card {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .corporate-card-header {
        padding: 15px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endpush
