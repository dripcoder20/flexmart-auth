<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class MobileVerificationController extends Controller
{
	public function index( )
    {
	    $key = Crypt::decrypt(request('token'));
	    $identifier = Cache::get($key);
	    $code = Cache::get('otp-'.$identifier);
	    $mobile = explode('-', $key)[1];
	    return view('verify', compact('code','mobile'));
    }
}
