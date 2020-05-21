<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('auth/login', 'Api\AuthController@store');

Route::get('auth/login', function () {
    return 'Auth/Login';
})->name('auth/login');


Route::get('signup', function () {
    return view('signup');
});

Route::get('verify', function () {
    // Temporary page
    return 'Verify';
});

