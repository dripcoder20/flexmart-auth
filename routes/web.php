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



Route::group(['middleware' => ['auth']], function () {
    Route::resource("", "ProfileController")->except('create');
    Route::delete("logout", 'AuthController@destroy');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('login', 'AuthController@index')->name('login');
    Route::post('login', 'AuthController@store');

    Route::get('signup', function () {
        return view('signup');
    });

    Route::get('verify', function () {
        // Temporary page
        return 'Verify';
    });
});
