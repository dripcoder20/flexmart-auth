<?php

namespace App\Http\Controllers\Api;

use App\Events\UserResetPasswordHasSucceeded;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetController extends Controller
{
    public function update(Request $request)
    {
        $request->validate(
            ['password' => ['required', 'confirmed']]
        );

        $mobileNumber = Cache::get($request->token);
        $user = User::where('mobile_number', $mobileNumber)->first();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        if ($user->email) {
            event(new UserResetPasswordHasSucceeded($user));
        }

        session()->flash('success', 'Password reset successful');

        return response()->json([
            'message' => 'Password reset successful'
        ], Response::HTTP_NO_CONTENT);
    }
}
