<?php

namespace App\Http\Controllers;

use App\User;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update()
    {
        return User::latest()->first();
    }
}
