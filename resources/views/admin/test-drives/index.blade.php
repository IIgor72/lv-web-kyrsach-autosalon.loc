@extends('layouts.app')

@section('title', 'Заявки на тест-драйвы')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Заявки на тест-драйвы</h1>
                </div>

                <div id="alert-container"></div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Автомобиль</th>
                            <th>Имя</th>
                            <th>Телефон</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($testDrives as $testDrive)
                            <tr id="test-drive-row-{{ $testDrive->id }}">
                                <td>{{ $testDrive->id }}</td>
                                <td>{{ $testDrive->car->name }}</td>
                                <td>{{ $testDrive->name }}</td>
                                <td>{{ $testDrive->phone }}</td>
                                <td>{{ $testDrive->created_at->format('d.m.Y H:i') }}</td>
                                <td>
{{--                                    <a href="{{ route('admin.test-drives.show', $testDrive) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>--}}
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-test-drive"
                                            data-id="{{ $testDrive->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $testDrives->links() }}
                </div>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertContainer = document.getElementById('alert-container');

            // Обработка удаления заявки
            document.querySelectorAll('.delete-test-drive').forEach(button => {
                button.addEventListener('click', function() {
                    const testDriveId = this.getAttribute('data-id');
                    if (confirm('Вы уверены, что хотите удалить эту заявку?')) {
                        deleteTestDrive(testDriveId);
                    }
                });
            });

            // Функция для удаления заявки
            function deleteTestDrive(id) {
                const url = `/admin/test-drives/${id}`;
                const token = document.querySelector('meta[name="csrf-token"]').content;

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
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

                            document.getElementById(`test-drive-row-${id}`).remove();
                            showAlert('success', data.message || 'Заявка успешно удалена');
                        } else {
                            showAlert('danger', data.message || 'Ошибка при удалении заявки');
                        }
                    })
                    .catch(error => {
                        let errorMessage = 'Произошла ошибка при удалении заявки';
                        if (error.errors) {
                            errorMessage = Object.values(error.errors).join('<br>');
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        showAlert('danger', errorMessage);
                    });
            }

            // Функция для показа уведомлений
            function showAlert(type, message) {
                const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

                alertContainer.innerHTML = alertHtml;

                setTimeout(() => {
                    const alert = alertContainer.querySelector('.alert');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            }
        });
    </script>
@endpush
