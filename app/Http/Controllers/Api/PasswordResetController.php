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
        $user = User::firstWhere('mobile_number', $mobileNumber);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        ($user->email) ? event(new UserResetPasswordHasSucceeded($user)) : '';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Password reset successful'
            ], Response::HTTP_NO_CONTENT);
        }

        return redirect('/login');
    }
}
