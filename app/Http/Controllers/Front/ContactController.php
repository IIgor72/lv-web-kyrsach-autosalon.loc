<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::firstOrCreate([
            'address' => 'г. Москва, ул. Автозаводская, д. 23',
            'phone' => '+7 (495) 123-45-67',
            'email' => 'info@autosalon.ru',
            'map_link' => 'https://yandex.ru/map-widget/v1/?um=constructor%3A1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p&source=constructor',
            'work_hours' => 'Пн-Пт: 9:00 - 20:00, Сб-Вс: 10:00 - 18:00',
        ]);

        return view('front.contacts', compact('contact'));
    }
}
