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
                        <p class="mb-0"><i class="bi bi-geo-alt-fill text-danger me-2"></i>г. Москва, ул. Автозаводская, д. 23</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="corporate-h2 h5">Телефоны</h3>
                        <p class="mb-0"><i class="bi bi-telephone-fill text-danger me-2"></i>+7 (495) 123-45-67</p>
                        <p class="mb-0"><i class="bi bi-telephone-fill text-danger me-2"></i>+7 (800) 123-45-67</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="corporate-h2 h5">Email</h3>
                        <p class="mb-0"><i class="bi bi-envelope-fill text-danger me-2"></i>info@autosalon.ru</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="corporate-h2 h5">Часы работы</h3>
                        <p class="mb-0"><i class="bi bi-clock-fill text-danger me-2"></i>Пн-Пт: 9:00 - 20:00</p>
                        <p class="mb-0"><i class="bi bi-clock-fill text-danger me-2"></i>Сб-Вс: 10:00 - 18:00</p>
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
                        src="https://yandex.ru/map-widget/v1/?um=constructor%3A1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p&amp;source=constructor"
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
