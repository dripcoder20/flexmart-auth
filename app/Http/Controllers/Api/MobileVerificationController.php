<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Libraries\Otp;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class MobileVerificationController extends Controller
{
    private const EXPIRATION = 1800; // 30 minutes

    public function store()
    {
        $key = Crypt::decrypt(request('token'));
        $otp = new Otp(Cache::get($key));

        request()->validate([
            'token' => 'required',
            'code' => [
                'required',
                function ($attribute, $value, $fail) use ($otp) {
                    if (! $otp->isValid($value)) {
                        $fail("Your verification code is either expired or invalid. Please try again.");
                    }
                }
            ]
        ]);

        $token = Hash::make($key);
        $mobile = Str::afterLast($key, '-');

        Cache::put($token, $mobile, self::EXPIRATION);

        return response()->json([
            'message' => 'Verification was successful.',
            'redirect_after' => session('redirect_after', config('app.url') . 'register') . '?token=' . $token,
            'confirmation_token' => $token,
        ], Response::HTTP_ACCEPTED);
    }

    public function update()
    {
        $key = Crypt::decrypt(request('token'));
        $otp = new Otp(Cache::get($key));
        $mobile = Str::afterLast($key, '-');

        $otp->send($mobile, function ($code) {
            // Temporary message
            return "Good day, please use $code as your verification code. Thank you";
        });

        if (session()->has('forgot')) {
            $user = User::where('mobile_number', $mobile)->first();
            ($user->email) ? $otp->sendEmail($user) : '';
        }

        return response()->json([
            'message' => "Code was sent."
        ], Response::HTTP_ACCEPTED);
    }
}
