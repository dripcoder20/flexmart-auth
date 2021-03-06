<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Libraries\Otp;

class MobileValidationController extends Controller
{
    private const CODE_EXPIRATION = 900; // 15 minutes
    private const VALID_MOBILE_REGEX = [
        '0[5-9]', '10', '12', '1[5-9]', '2[0-9]', '3[0-9]', '4[0-3]', '4[5-9]', '50',
        '5[5-6]', '61', '6[5-7]', '7[3-5]', '7[7-9]', '9[5-9]'
    ];

    public function store(Request $request)
    {
        $validation = session('mobile:validation', 'unique:users,mobile_number');

        $request->validate(['mobile_number' => [
                'required',
                $validation,
                'regex:/^\+639(' . implode('|', self::VALID_MOBILE_REGEX) . ')\d{7}/'
            ]
        ]);

        $token = $this->handleOtp(request('mobile_number'));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Mobile was validated',
                'token' => $token,
            ], Response::HTTP_CREATED);
        }

        return redirect('/mobile/verify?token=' . $token);
    }

    private function handleOtp($mobile)
    {
        $otp = new Otp();

        $otp->send($mobile, function ($code) {
            return "Good day, please use $code as your verification code. Thank you";
        });

        if (session()->has('forgot')) {
            $user = User::firstWhere('mobile_number', $mobile);
            ($user->email) ? $otp->sendEmail($user) : '';
        }

        Cache::put(session('key_prefix', 'verified-') . $mobile, $otp->getIdentifier(), self::CODE_EXPIRATION);

        return Crypt::encrypt(session('key_prefix', 'verified-') . $mobile);
    }
}
