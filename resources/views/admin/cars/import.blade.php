@extends('layouts.app')

@section('title', 'Импорт каталога автомобилей')

@section('content')
    <div class="container-fluid">
        <div class="row">
{{--            @include('admin.partials.sidebar')--}}

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Импорт каталога автомобилей</h1>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.admin.cars.import') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="file" class="form-label">Файл данных (CSV/Excel)</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                                <div class="form-text">
                                    Формат файла: name, type, price, description, photo, photo_title
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="photos_archive" class="form-label">Архив с фотографиями (ZIP, опционально)</label>
                                <input type="file" class="form-control" id="photos_archive" name="photos_archive">
                                <div class="form-text">
                                    ZIP архив с фотографиями. Имена файлов должны соответствовать указанным в файле данных.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Импортировать</button>
                            <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">Отмена</a>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        Пример CSV файла
                    </div>
                    <div class="card-body">
                    <pre>car_type_id,name,slug,description,price,engine,power,color,image,is_active
1,BMW X4,bmw-x4,"Рекомендации и поддержание мощных двигателей",8000000000,3.0L,340,Черный,bmw_x5.jpg,1
2,Audi A3,audi-a3,"Элективный баланс-класса",45000000,4.0L,100,Серый,audi_a4.jpg,1
</pre>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
