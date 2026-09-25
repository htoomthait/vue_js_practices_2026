<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Get the contats as list from database response as json
     * @author 2026-09-24
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContacts(){
        $contacts = Contact::query()->get();

        return response()->json([
            "code" => 200,
            "message" => "Contacts are listed as follow",
            "contacts"=> $contacts
        ]);
    }

    /**
     * Creating new contact with validated input
     * @author Htoo Maung Thait (htoomaungthait@gmail.com)
     * @since 2026-09-25
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerContact(Request $request){
        $request->validate([
            "name" => "required|min:3",
            "email"=> "required|email",
            "designation" => "required|min:2",
            "contact_no" => "required"
        ]);

        $newContact = Contact::create([
            "name"=> $request->name,
            "email"=> $request->email,
            "designation" => $request->designation,
            "contact_no"=> $request->contact_no,
        ]);

        return response()->json([
            "code"=> 201,
            "message"=> "New contact has been created successfully!",
            "contact" => $newContact
        ]);
    }
}
