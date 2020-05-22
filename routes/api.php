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

    Route::get('/me', 'Api\AuthController@show');

    Route::delete('/logout', 'Api\AuthController@destroy');
});

Route::post('login', 'Api\AuthController@store');
Route::post('register', 'RegistrationController@register');

