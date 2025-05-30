@extends('layouts.app')

@section('title', 'Редактировать пользователя')

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{--            @include('admin.partials.sidebar')--}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Редактировать пользователя: {{ $user->name }}</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Назад к списку
                            </a>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Имя пользователя</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Новый пароль</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password">
                                        <div class="form-text">Оставьте пустым, если не хотите менять пароль</div>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                                        <input type="password" class="form-control"
                                               id="password_confirmation" name="password_confirmation">
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Роль</label>
                                        <select class="form-select @error('role') is-invalid @enderror"
                                                id="role" name="role" required>
                                            <option value="">Выберите роль</option>
                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                                Администратор
                                            </option>
                                        </select>
                                        @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-lg"></i> Сохранить изменения
                                        </button>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                            Отмена
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Информация о пользователе</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>ID:</strong> {{ $user->id }}
                                </div>
                                <div class="mb-3">
                                    <strong>Создан:</strong><br>
                                    {{ $user->created_at->format('d.m.Y H:i') }}
                                </div>
                                <div class="mb-3">
                                    <strong>Обновлен:</strong><br>
                                    {{ $user->updated_at->format('d.m.Y H:i') }}
                                </div>
                                @if($user->email_verified_at)
                                    <div class="mb-3">
                                        <strong>Email подтвержден:</strong><br>
                                        {{ $user->email_verified_at->format('d.m.Y H:i') }}
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <span class="badge bg-warning">Email не подтвержден</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">Информация о ролях</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Администратор</strong>
                                    <p class="text-muted small">Полный доступ ко всем функциям системы</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
