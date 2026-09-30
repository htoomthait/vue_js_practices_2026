<?php

use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:api')->group(function () {
    Route::get('/landing-home', [HomeController::class, 'landingApiCall']);
});

Route::get('/landing', [HomeController::class, 'landingApiCall']);



Route::get('/contacts', [ContactController::class,'getContacts']);


Route::post('/contacts', [ContactController::class,'registerContact']);

Route::get('/contacts/{id}', [ContactController::class,'getContactById']);

Route::put('/contacts/{id}', [ContactController::class,'updateContactById']);

Route::delete('/contacts/{id}', [ContactController::class,'deleteContactById']);
