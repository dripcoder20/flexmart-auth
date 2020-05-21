<?php

namespace App\Http\Controllers\Api;

use App\Events\UserHasLoggedIn;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class AuthController extends Controller
{

    public function show()
    {
        return auth()->user();
    }

    /**
     * store
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        if (Auth::attempt($request->only('mobile_number', 'password'))) {
            $user = auth()->user();
            $ip_address = $request->header('x-forwarded-for') ?: $request->ip();
            
            event(new UserHasLoggedIn(
                $user,
                $request->only('device_name'),
                $ip_address
            ));

            return response()->json(
                ['token' => $user->createToken($request->device_name)->plainTextToken]
            );
        }

        throw new AuthenticationException("Invalid credentials provided");
    }

    public function destroy()
    {
        cookie()->forget('xsrf-token');
        $user = auth()->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Logout successful'], 202);
    }
}
