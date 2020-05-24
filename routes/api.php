<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/auth/user', 'Api\AuthController@show');

    Route::delete('/auth/logout', 'Api\AuthController@destroy');
});

Route::post('auth/login', 'Api\AuthController@store');
Route::post('register', 'RegistrationController@register');

Route::post('verify/resend', 'VerificationController@resend');