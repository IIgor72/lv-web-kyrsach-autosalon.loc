@extends('layouts.app')

@section('title', 'Добавление нового автомобиля')

@section('content')
    <div class="container-fluid">
        <div class="row">
{{--            @include('admin.partials.sidebar')--}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Добавление нового автомобиля</h1>
                </div>

                <form method="POST" action="{{ route('admin.cars.store') }}">
                    @csrf
                    @include('admin.cars._form')

                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">Отмена</a>
                </form>
            </main>
        </div>
    </div>
@endsection
