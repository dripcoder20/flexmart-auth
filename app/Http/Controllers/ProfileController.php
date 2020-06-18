<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update()
    {
        return view('update-profile');
    }
}
