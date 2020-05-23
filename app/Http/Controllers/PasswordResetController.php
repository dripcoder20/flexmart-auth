<?php

namespace App\Http\Controllers;

class PasswordResetController extends Controller
{
    public function show()
    {
        return view('reset-password');
    }

    public function index()
    {
        session()->put('forgot', true);
        session()->put('mobile:validation', 'exists:users,mobile_number');
        session()->put('redirect_after', config("app.url") . "/reset-password");
        session()->put('key_prefix', 'verified-forgot-');

        return redirect('/mobile/validate');
    }
}
