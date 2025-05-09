@extends('layouts.app')

@section('title', 'Управление автомобилями')

@section('content')
    <div class="container-fluid">
        <div class="row">
{{--            @include('admin.partials.sidebar')--}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Управление автомобилями</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Добавить автомобиль
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Тип</th>
                            <th>Цена</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cars as $car)
                            <tr>
                                <td>{{ $car->id }}</td>
                                <td>{{ $car->name }}</td>
                                <td>{{ $car->type->name }}</td>
                                <td>{{ number_format($car->price, 0, ',', ' ') }} ₽</td>
                                <td>
                                <span class="badge bg-{{ $car->is_active ? 'success' : 'secondary' }}">
                                    {{ $car->is_active ? 'Активен' : 'Неактивен' }}
                                </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.cars.edit', $car) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить этот автомобиль?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $cars->links() }}
                </div>
            </main>
        </div>
    </div>
@endsection
