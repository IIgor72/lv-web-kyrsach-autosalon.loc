<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestDriveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|regex:/^[а-яА-ЯёЁ\s]+$/u',
            'phone' => 'required|string|max:20|regex:/^\+?\d{1}?\s?\(?\d{3}\)?\s?\d{3}-?\d{2}-?\d{2}$/',
            'email' => 'required|email|max:255',
            'date' => 'required|date|after:today',
            'time' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'ФИО должно содержать только русские буквы',
            'phone.regex' => 'Укажите телефон в формате +7 (XXX) XXX-XX-XX',
            'date.after' => 'Дата тест-драйва должна быть не ранее завтрашнего дня',
        ];
    }
}
