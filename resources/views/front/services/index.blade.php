@extends('layouts.app')

@section('title', 'Сервис и обслуживание')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Сервис и обслуживание</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h4">Техническое обслуживание</h2>
                <p>Мы предлагаем полный спектр услуг по техническому обслуживанию вашего автомобиля.</p>
                <ul>
                    <li>Плановое ТО</li>
                    <li>Диагностика</li>
                    <li>Ремонт двигателя</li>
                    <li>Замена масла и фильтров</li>
                </ul>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h4">Запчасти</h2>
                <p>Оригинальные запчасти и расходные материалы для вашего автомобиля.</p>
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="h5">Преимущества:</h3>
                        <ul>
                            <li>Гарантия качества</li>
                            <li>Оригинальные комплектующие</li>
                            <li>Доступные цены</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h3 class="h5">Как заказать?</h3>
                        <p>Оставьте заявку по телефону или через форму на сайте</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
