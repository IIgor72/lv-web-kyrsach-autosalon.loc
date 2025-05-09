<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:news,slug',
            'content' => 'required|string',
            'published_at' => 'required|date',
            'is_active' => 'boolean'
        ]);

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Новость успешно добавлена');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:news,slug,'.$news->id,
            'content' => 'required|string',
            'published_at' => 'required|date',
            'is_active' => 'boolean'
        ]);

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Новость успешно обновлена');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')
            ->with('success', 'Новость успешно удалена');
    }
}
