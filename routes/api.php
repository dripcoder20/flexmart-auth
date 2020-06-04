<?php

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
    Route::get('/me', 'Api\AuthController@show');

    Route::delete('/logout', 'Api\AuthController@destroy');
});

Route::post('login', 'Api\AuthController@store');
Route::post('validate', 'Api\MobileValidationController@store');
Route::post('register', 'Api\RegistrationController@store');
Route::post('verify', 'Api\VerificationController@store');
Route::post('resend-verification', 'Api\VerificationController@update');
