@extends('layouts.app')

@section('title', 'Контакты - Админ')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (предполагается, что он уже есть в layouts.app) -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
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
                            <a class="nav-link text-white" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2"></i>Пользователи
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="{{ route('admin.contacts.index') }}">
                                <i class="bi bi-telephone-fill me-2"></i>Контакты
                            </a>
                        </li>
                    </ul>

                    <hr class="bg-secondary">

                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <form method="POST" action="{{ route('auth.logout') }}">
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
                    <h1 class="h2">Контакты</h1>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h2 class="h4 mb-0">Редактировать контактную информацию</h2>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.contacts.update', $contact) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Адрес</label>
                                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $contact->address ?? 'г. Москва, ул. Автозаводская, д. 23') }}" required>
                                        @error('address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone1" class="form-label">Телефон 1</label>
                                        <input type="text" class="form-control" id="phone1" name="phone1" value="{{ old('phone1', $contact->phone1 ?? '+7 (495) 123-45-67') }}" required>
                                        @error('phone1')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone2" class="form-label">Телефон 2</label>
                                        <input type="text" class="form-control" id="phone2" name="phone2" value="{{ old('phone2', $contact->phone2 ?? '+7 (800) 123-45-67') }}">
                                        @error('phone2')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $contact->email ?? 'info@autosalon.ru') }}" required>
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="work_hours_weekdays" class="form-label">Часы работы (Пн-Пт)</label>
                                        <input type="text" class="form-control" id="work_hours_weekdays" name="work_hours_weekdays" value="{{ old('work_hours_weekdays', $contact->work_hours_weekdays ?? 'Пн-Пт: 9:00 - 20:00') }}" required>
                                        @error('work_hours_weekdays')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="work_hours_weekends" class="form-label">Часы работы (Сб-Вс)</label>
                                        <input type="text" class="form-control" id="work_hours_weekends" name="work_hours_weekends" value="{{ old('work_hours_weekends', $contact->work_hours_weekends ?? 'Сб-Вс: 10:00 - 18:00') }}" required>
                                        @error('work_hours_weekends')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h2 class="h4 mb-0">Схема проезда</h2>
                            </div>
                            <div class="card-body p-0">
                                <!-- Яндекс.Карта -->
                                <iframe
                                    src="https://yandex.ru/map-widget/v1/?um=constructor%3A1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p&source=constructor"
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
            </main>
        </div>
    </div>
@endsection
