<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::post("/auth/login", [AuthController::class, "login"]);



Route::middleware('auth:api')->group(function () {
    Route::get('/landing-home', [HomeController::class, 'landingApiCall']);


    Route::post('/auth/logout', [AuthController::class,'logout']);
});

Route::get('/landing', [HomeController::class, 'landingApiCall']);



Route::get('/contacts', [ContactController::class,'getContacts']);


Route::post('/contacts', [ContactController::class,'registerContact']);

Route::get('/contacts/{id}', [ContactController::class,'getContactById']);

Route::put('/contacts/{id}', [ContactController::class,'updateContactById']);

Route::delete('/contacts/{id}', [ContactController::class,'deleteContactById']);
