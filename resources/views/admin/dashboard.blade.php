@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Панель управления
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('admin.cars.index') }}">
                                <i class="bi bi-car-front me-2"></i>Автомобили
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('admin.news.index') }}">
                                <i class="bi bi-newspaper me-2"></i>Новости
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('admin.test-drives.index') }}">
                                <i class="bi bi-calendar-check me-2"></i>Тест-драйвы
                            </a>
                        </li>
                        <li class="nav-item">
{{--                            <a class="nav-link text-white" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2"></i>Пользователи
                            </a>--}}
                        </li>
                    </ul>

                    <hr class="bg-secondary">

                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link text-white bg-transparent border-0 w-100 text-start">
                                    <i class="bi bi-box-arrow-right me-2"></i>Выйти
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Панель управления</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Экспорт</button>
                        </div>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-primary h-100">
                            <div class="card-body">
                                <h5 class="card-title">Автомобили</h5>
                                <p class="card-text display-6">{{ $carsCount ?? 0 }}</p>
                                <a href="{{ route('admin.cars.index') }}" class="text-white text-decoration-none">
                                    Управление <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-success h-100">
                            <div class="card-body">
                                <h5 class="card-title">Новости</h5>
                                <p class="card-text display-6">{{ $newsCount ?? 0 }}</p>
                                <a href="{{ route('admin.news.index') }}" class="text-white text-decoration-none">
                                    Управление <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-warning h-100">
                            <div class="card-body">
                                <h5 class="card-title">Тест-драйвы</h5>
                                <p class="card-text display-6">{{ $testDrivesCount ?? 0 }}</p>
                                <a href="{{ route('admin.test-drives.index') }}" class="text-white text-decoration-none">
                                    Управление <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-danger h-100">
                            <div class="card-body">
                                <h5 class="card-title">Пользователи</h5>
                                <p class="card-text display-6">{{ $usersCount ?? 0 }}</p>
{{--                                <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none">
                                    Управление <i class="bi bi-arrow-right"></i>
                                </a>--}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                Быстрые действия
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-2"></i>Добавить автомобиль
                                    </a>
                                    <a href="{{ route('admin.news.create') }}" class="btn btn-success">
                                        <i class="bi bi-plus-lg me-2"></i>Добавить новость
                                    </a>
{{--                                    <a href="{{ route('admin.users.create') }}" class="btn btn-warning">
                                        <i class="bi bi-person-plus me-2"></i>Добавить пользователя
                                    </a>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                Системная информация
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Версия PHP
                                        <span class="badge bg-primary rounded-pill">{{ PHP_VERSION }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Режим
                                        <span class="badge bg-{{ app()->environment('production') ? 'danger' : 'success' }} rounded-pill">
                                        {{ app()->environment() }}
                                    </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Время сервера
                                        <span class="badge bg-secondary rounded-pill">{{ now()->format('H:i:s') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
