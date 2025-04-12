<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsTableSeeder extends Seeder
{
    public function run()
    {
        $newsItems = [
            ['title' => 'Новая коллекция BMW 2023', 'content' => 'Представляем новые модели BMW 2023 года с улучшенными характеристиками...', 'image' => 'bmw_news.jpg', 'is_active' => true, 'published_at' => '2023-05-15 10:00:00', 'type' => 'news'],
            ['title' => 'Скидки на Audi до 15%', 'content' => 'Только в этом месяце специальные условия кредитования на все модели Audi...', 'image' => 'audi_promo.jpg', 'is_active' => true, 'published_at' => '2023-06-01 09:30:00', 'type' => 'promotion'],
            ['title' => 'Тест-драйв Mercedes E-Class', 'content' => 'Наши впечатления от тест-драйва нового Mercedes E-Class с полным обзором...', 'image' => 'mercedes_test.jpg', 'is_active' => true, 'published_at' => '2023-04-20 14:15:00', 'type' => 'news'],
            ['title' => 'Летняя акция на шиномонтаж', 'content' => 'При покупке любого автомобиля - бесплатный шиномонтаж в подарок!', 'image' => 'tyres_promo.jpg', 'is_active' => true, 'published_at' => '2023-05-30 11:20:00', 'type' => 'promotion'],
            ['title' => 'Электрический кроссовер от Toyota', 'content' => 'Toyota анонсировала свой первый полностью электрический кроссовер...', 'image' => 'toyota_ev.jpg', 'is_active' => true, 'published_at' => '2023-06-10 16:45:00', 'type' => 'news'],
            ['title' => 'Специальные условия лизинга', 'content' => 'Уникальное предложение по лизингу для бизнес-клиентов...', 'image' => 'leasing_offer.jpg', 'is_active' => false, 'published_at' => '2023-03-15 13:10:00', 'type' => 'promotion'],
            ['title' => 'Автосалон нового формата', 'content' => 'Открытие нового современного автосалона в центре города...', 'image' => 'new_showroom.jpg', 'is_active' => true, 'published_at' => '2023-06-05 12:00:00', 'type' => 'news'],
            ['title' => 'Бесплатное ТО при покупке', 'content' => 'Приобретая автомобиль у нас, вы получаете 3 года бесплатного ТО...', 'image' => 'free_service.jpg', 'is_active' => true, 'published_at' => '2023-05-20 10:30:00', 'type' => 'promotion'],
            ['title' => 'Новые технологии безопасности', 'content' => 'Обзор инновационных систем безопасности в автомобилях 2023 года...', 'image' => 'safety_tech.jpg', 'is_active' => true, 'published_at' => '2023-04-10 15:20:00', 'type' => 'news'],
            ['title' => 'Распродажа демо-автомобилей', 'content' => 'Специальные цены на автомобили с пробегом из нашего автопарка...', 'image' => 'demo_sale.jpg', 'is_active' => true, 'published_at' => '2023-06-15 09:00:00', 'type' => 'promotion']
        ];

        foreach ($newsItems as $news) {
            News::create($news);
        }
    }
}
