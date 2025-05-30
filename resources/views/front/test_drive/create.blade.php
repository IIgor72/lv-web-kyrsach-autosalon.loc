@extends('layouts.app')

@section('title', 'Запись на тест-драйв '.$car->name)

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                    <!-- Заголовок с изображением автомобиля -->
                    <div class="card-header bg-dark text-white position-relative">
                        <div class="position-absolute top-0 end-0 p-3 bg-primary rounded-bottom-start">
                            <span class="h5 mb-0">{{ number_format($car->price, 0, '', ' ') }} ₽</span>
                        </div>
                        <h2 class="mb-1"><i class="fas fa-car me-2"></i>Тест-драйв {{ $car->name }}</h2>
                        <div class="text-muted">{{ $car->engine }}, {{ $car->power }} л.с.</div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <!-- Блок для вывода уведомлений -->
                        <div id="alert-container"></div>

                        <!-- Форма -->
                        <form id="test-drive-form" action="{{ route('test-drive.store', $car) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="car_id" value="{{ $car->id }}">

                            <div class="row g-3">
                                <!-- ФИО -->
                                <div class="col-md-12">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-1 text-primary"></i> ФИО <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                        <input type="text" class="form-control" id="name" name="name"
                                               placeholder="Иванов Иван Иванович" required>
                                        <div class="invalid-feedback">Пожалуйста, укажите ваше ФИО</div>
                                    </div>
                                </div>

                                <!-- Контакты -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-1 text-primary"></i> Телефон <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                               placeholder="+7 (900) 123-45-67" required>
                                        <div class="invalid-feedback">Укажите корректный телефон</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1 text-primary"></i> Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                               placeholder="example@mail.ru" required>
                                        <div class="invalid-feedback">Укажите корректный email</div>
                                    </div>
                                </div>

                                <!-- Дата и время -->
                                <div class="col-md-6">
                                    <label for="date" class="form-label">
                                        <i class="fas fa-calendar-day me-1 text-primary"></i> Дата <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-calendar text-muted"></i></span>
                                        <input type="date" class="form-control" id="date" name="date"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                        <div class="invalid-feedback">Выберите дату тест-драйва</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="time" class="form-label">
                                        <i class="fas fa-clock me-1 text-primary"></i> Время <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-clock text-muted"></i></span>
                                        <select class="form-select" id="time" name="time" required>
                                            <option value="" selected disabled>Выберите время</option>
                                            @foreach(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'] as $time)
                                                <option value="{{ $time }}">{{ $time }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Выберите время тест-драйва</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="d-flex justify-content-between mt-5">
                                <a href="{{ route('cars.show', $car->slug) }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-arrow-left me-2"></i> Назад
                                </a>
                                <button type="submit" class="btn btn-primary px-4" id="submit-button">
                                    <i class="fas fa-paper-plane me-2"></i> Отправить заявку
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border: none;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            padding: 1.5rem;
        }
        .form-label {
            font-weight: 500;
        }
        .input-group-text {
            min-width: 45px;
            justify-content: center;
        }
        .btn {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
        }
        .invalid-feedback {
            display: none;
            font-size: 0.85rem;
        }
        .was-validated .form-control:invalid ~ .invalid-feedback,
        .was-validated .form-select:invalid ~ .invalid-feedback {
            display: block;
        }
        #submit-button.loading {
            position: relative;
            pointer-events: none;
        }
        #submit-button.loading:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('test-drive-form');
            const alertContainer = document.getElementById('alert-container');
            const submitButton = document.getElementById('submit-button');

            // Валидация формы
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                if (!form.checkValidity()) {
                    event.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                submitForm();
            });

            // Маска для телефона
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                    e.target.value = !x[2] ? x[1] : x[1] + ' (' + x[2] + ') ' + x[3] + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
                });
            }

            // Функция для отправки формы
            function submitForm() {
                const formData = new FormData(form);
                submitButton.classList.add('loading');
                submitButton.disabled = true;

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            form.reset();
                            form.classList.remove('was-validated');
                        } else {
                            showAlert('danger', data.message || 'Произошла ошибка при отправке формы');
                        }
                    })
                    .catch(error => {
                        let errorMessage = 'Произошла ошибка при отправке формы';
                        if (error.errors) {
                            errorMessage = Object.values(error.errors).join('<br>');
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        showAlert('danger', errorMessage);
                    })
                    .finally(() => {
                        submitButton.classList.remove('loading');
                        submitButton.disabled = false;
                    });
            }

            // Функция для показа уведомлений
            function showAlert(type, message) {
                const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show mb-4">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

                alertContainer.innerHTML = alertHtml;

                if (type === 'success') {
                    setTimeout(() => {
                        const alert = alertContainer.querySelector('.alert');
                        if (alert) {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        }
                    }, 5000);
                }
            }
        });
    </script>
@endpush
