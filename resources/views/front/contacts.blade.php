@extends('layouts.app')

@section('title', 'Контакты')

@section('styles')


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
                    <div id="map" style="height: 400px;"></div>
                </div>
                <div class="card-footer bg-white">
                    <a href="#" class="corporate-btn btn">Построить маршрут</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function initMap() {
        const location = { lat: 55.706, lng: 37.655 };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location,
            styles: [
                {
                    "featureType": "all",
                    "elementType": "all",
                    "stylers": [
                        { "saturation": -100 }
                    ]
                }
            ]
        });

        new google.maps.Marker({
            position: location,
            map: map,
            title: "Наш автосалон",
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png"
            }
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
@endpush
