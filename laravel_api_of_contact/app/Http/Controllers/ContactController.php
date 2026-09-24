<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function getContacts(){
        $contacts = Contact::query()->get();

        return response()->json([
            "code" => 200,
            "message" => "Contacts are listed as follow",
            "contacts"=> $contacts
        ]);
    }
}
