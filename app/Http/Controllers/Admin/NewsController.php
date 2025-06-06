<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Отображение списка новостей
     */
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(15);

        // Определяем, какой шаблон использовать в зависимости от роли
        $view = 'admin.news.index';

        // Если пользователь зашел через маршрут менеджера
        if (request()->route()->getPrefix() === 'manager/news') {
            $view = 'manager.news.index';
        }

        return view($view, compact('news'));
    }

    /**
     * Показать форму создания новости
     */
    public function create()
    {
        $view = 'admin.news.create';

        if (request()->route()->getPrefix() === 'manager/news') {
            $view = 'manager.news.create';
        }

        return view($view);
    }

    /**
     * Сохранить новость
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:news,promotion,event',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news = News::create($data);

        $redirectRoute = auth()->user()->isAdmin() ? 'admin.news.index' : 'manager.news.index';

        return redirect()->route($redirectRoute)->with('success', 'Новость успешно создана!');
    }

    /**
     * Показать форму редактирования
     */
    public function edit(News $news)
    {
        $view = 'admin.news.edit';

        if (request()->route()->getPrefix() === 'manager/news') {
            $view = 'manager.news.edit';
        }

        return view($view, compact('news'));
    }

    /**
     * Обновить новость
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:news,promotion,event',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        $redirectRoute = auth()->user()->isAdmin() ? 'admin.news.index' : 'manager.news.index';

        return redirect()->route($redirectRoute)->with('success', 'Новость успешно обновлена!');
    }

    /**
     * Удалить новость
     */
    public function destroy(News $news)
    {
        // Удаляем изображение, если оно есть
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        $redirectRoute = auth()->user()->isAdmin() ? 'admin.news.index' : 'manager.news.index';

        return redirect()->route($redirectRoute)->with('success', 'Новость успешно удалена!');
    }
}
