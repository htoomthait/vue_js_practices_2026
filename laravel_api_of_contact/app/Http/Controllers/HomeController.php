<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function landingApiCall(Request $request){

        return response()->json([
            'code' => 200,
            'message' => "Welcome from the laravel crud app!"
        ]);
    }
}
