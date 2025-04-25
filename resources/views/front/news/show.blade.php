@extends('layouts.app')

@section('title', $news->title)

@section('content')
<div class="container-fluid py-4 px-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <article class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="mb-3">{{ $news->title }}</h1>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge bg-{{ $news->type === 'news' ? 'primary' : 'danger' }} fs-6">
                            {{ $news->type === 'news' ? 'Новость' : 'Акция' }}
                        </span>
                        @if($news->published_at)
                        <span class="text-muted">Опубликовано: {{ $news->published_at->format('d.m.Y H:i') }}</span>
                        @endif
                    </div>

                    @if($news->image)
                    <div class="mb-4 text-center">
                        <img src="{{ asset('storage/' . $news->image) }}"
                             class="img-fluid rounded w-75 mx-auto d-block"
                             alt="{{ $news->title }}">
                    </div>
                    @endif

                    <div class="news-content">
                        {!! $news->content ?? '<p class="text-muted">Содержание новости отсутствует</p>' !!}
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Назад к списку новостей
                        </a>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection
