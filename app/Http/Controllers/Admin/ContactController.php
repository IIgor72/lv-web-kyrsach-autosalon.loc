<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::firstOrCreate(); // Использует HasFactory для создания записи, если её нет
        return view('admin.contacts.index', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'phone1' => 'required|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'work_hours_weekdays' => 'required|string|max:50',
            'work_hours_weekends' => 'required|string|max:50',
        ]);

        $contact->update($request->all());

        return redirect()->route('admin.contacts.index')->with('status', 'Контактная информация успешно обновлена!');
    }
}
