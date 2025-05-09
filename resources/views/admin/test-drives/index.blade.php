@extends('layouts.app')

@section('title', 'Заявки на тест-драйвы')

@section('content')
    <div class="container-fluid">
        <div class="row">
{{--            @include('admin.partials.sidebar')--}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Заявки на тест-драйвы</h1>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

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
                            <tr>
                                <td>{{ $testDrive->id }}</td>
                                <td>{{ $testDrive->car->name }}</td>
                                <td>{{ $testDrive->name }}</td>
                                <td>{{ $testDrive->phone }}</td>
                                <td>{{ $testDrive->created_at->format('d.m.Y H:i') }}</td>
                                <td>
{{--                                    <a href="{{ route('admin.test-drives.show', $testDrive) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>--}}
                                    <form action="{{ route('admin.test-drives.destroy', $testDrive) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить эту заявку?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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
