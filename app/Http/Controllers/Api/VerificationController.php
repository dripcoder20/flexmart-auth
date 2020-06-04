<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Libraries\Otp;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class VerificationController extends Controller
{
    private const EXPIRATION = 1800; // 30 minutes

    public function store()
    {
        $key = Crypt::decrypt(request('token'));
        $otp = new Otp(Cache::get($key));

        request()->validate([
            'token' => 'required',
            'code'  => [
                'required',
                function ($attribute, $value, $fail) use ($otp) {
                    if (! $otp->isValid($value)) {
                        $fail("Your verification code is either expired or invalid. Please try again.");
                    }
                }
            ]
        ]);

        $token = Hash::make($key);
        $mobile = explode('-', $key)[1];
        Cache::put($token, $mobile, self::EXPIRATION);

        return response()->json([
            'message' => 'Verification was successful.',
            'confirmation_token' => $token,
        ], Response::HTTP_ACCEPTED);
    }

    public function update()
    {
        $key = Crypt::decrypt(request('token'));
        $otp = new Otp(Cache::get($key));
        $mobile = explode('-', $key)[1];

        $otp->send($mobile, function ($code) {
            // Temporary message
            return "Good day, please use $code as your verification code. Thank you";
        });

        return response()->json([
            'message' => "Code was sent."
        ], Response::HTTP_ACCEPTED);
    }
}
