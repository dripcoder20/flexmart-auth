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
    Route::get("logout", 'AuthController@destroy');
});


Route::group(['middleware' => 'guest'], function () {
    Route::get('login', 'AuthController@index')->name('login');
    Route::post('login', 'AuthController@store');
    Route::get('signup', 'SignupController@index');
    Route::get('mobile/verify', 'MobileVerificationController@index');
    Route::get('update-profile', 'ProfileController@update');
});

// All anonymous routes will go here
Route::group(['middleware' => 'guest'], function () {
    Route::get('mobile/validate', function () {
        return view('validate-mobile');
    });
    Route::get('signup', function () {
        session()->put('mobile:validation', 'unique:users,mobile_number');
        session()->put('redirect_after', config("app.url") . "/register");

        return redirect('/mobile/validate');
    });

    Route::get('forgot', function () {
        // Todo add test to validate session exists when forgot password is selected
        session()->put('mobile:validation', 'exists:users,mobile_number');
        session()->put('redirect_after', config("app.url") . "/reset-password");

        return redirect('/mobile/validate');
    });

    Route::get('register', function () {
        return view('create-account');
    });
});
