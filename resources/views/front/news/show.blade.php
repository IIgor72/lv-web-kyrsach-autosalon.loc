@extends('layouts.app')

@section('title', $news->title)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <article>
            <h1 class="mb-3">{{ $news->title }}</h1>

            <div class="d-flex justify-content-between mb-3">
                <span class="badge bg-{{ $news->type === 'news' ? 'primary' : 'danger' }}">
                    {{ $news->type === 'news' ? 'Новость' : 'Акция' }}
                </span>
                <span class="text-muted">Опубликовано: {{ $news->published_at->format('d.m.Y H:i') }}</span>
            </div>

            @if($news->image)
            <img src="{{ asset('storage/' . $news->image) }}" class="img-fluid rounded mb-4" alt="{{ $news->title }}">
            @endif

            <div class="news-content">
                {!! $news->content !!}
            </div>
        </article>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Другие новости</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($relatedNews as $item)
                    <a href="{{ route('news.show', $item->id) }}" class="list-group-item list-group-item-action">
                        {{ $item->title }}
                        <small class="d-block text-muted">{{ $item->published_at->format('d.m.Y') }}</small>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .news-content img {
        max-width: 100%;
        height: auto;
        margin: 1rem 0;
    }
</style>
@endpush
