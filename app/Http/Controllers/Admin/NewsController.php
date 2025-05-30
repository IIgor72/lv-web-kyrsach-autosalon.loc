<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'content' => 'required|string',
            'type' => 'nullable|string|in:news,promotion,event',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        // Обработка загрузки изображения
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        // Устанавливаем is_active в false, если не передан (чекбокс не отмечен)
        $validated['is_active'] = $request->has('is_active');

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
            'content' => 'required|string',
            'type' => 'nullable|string|in:news,promotion,event',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        // Обработка загрузки нового изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если оно есть
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        // Устанавливаем is_active в false, если не передан (чекбокс не отмечен)
        $validated['is_active'] = $request->has('is_active');

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Новость успешно обновлена');
    }

    public function destroy(News $news)
    {
        // Удаляем изображение при удалении новости
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();
        return redirect()->route('admin.news.index')
            ->with('success', 'Новость успешно удалена');
    }
}
