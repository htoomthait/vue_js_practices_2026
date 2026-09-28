<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Get the contats as list from database response as json
     * @author Htoo Maung Thait (htoomaungthait@gmail.com)
     * @since 2026-09-24
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContacts(){
        $contacts = Contact::query()->orderByDesc("id")->get();

        return response()->json([
            "code" => 200,
            "message" => "Contacts are listed as follow",
            "contacts"=> $contacts
        ],200);
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
            "message"=> "New contact {$newContact->name} has been created successfully!",
            "contact" => $newContact
        ], 201);
    }

    /**
     * To get the contact with the ID given
     * @author Htoo Maung Thait (htoomaungthait@gmail.com)
     * @since 2026-09-28
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContactById($id){

        $contact = Contact::find($id);

        $responseCode = $contact ? 200 : 404;
        $message = $contact ? "Contact with id {$id} has been found successfully.": "Contact with id {$id} is not found.";


        return response()->json([
            "code"=> $responseCode,
            "message"=> $message,
            "contact" => $contact
        ], $responseCode);
    }

    /**
     * Update the contact with given ID to given submitted payload
     * @author Htoo Maung Thait (htoomaungthait@gmail.com)
     * @since 2026-09-28
     * @param Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateContactById(Request $request, $id){
         $request->validate([
            "name" => "required|min:3",
            "email"=> "required|email",
            "designation" => "required|min:2",
            "contact_no" => "required"
        ]);

        $contact = Contact::find($id);
        $responseCode = 0;
        $message = "";

        if( $contact ){
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->designation = $request->designation;
            $contact->contact_no = $request->contact_no;

            $contact->save();

            $responseCode = 200;
            $message = "Contact with id {$id} has been updated successfully!";
        }
        else{
            $responseCode = 404;
            $message = "Contact with id {$id} is not found.";
        }

        return response()->json([
            "code"=> $responseCode,
            "message"=> $message,
            "contact" => $contact
        ], $responseCode);
    }

    /**
     * Delete contact by Id
     * @author Htoo Maung Thait (htoomaungthait@gmail.com)
     * @since 2026-09-28
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public  function deleteContactById ($id){
        $contact = Contact::find($id);
        $responseCode = 0;
        $message = "";

        if($contact){
            $contact->delete();
            $responseCode = 200;
            $message = "Contact with id {$id} has been deleted successfully.";
        }else{
            $responseCode = 404;
            $message = "Contact with id {$id} is not found.";
        }

        return response()->json([
            "code"=> $responseCode,
            "message"=> $message,
            "contact" => $contact
        ], $responseCode);

    }
}
