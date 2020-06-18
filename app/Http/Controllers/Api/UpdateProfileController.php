<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UpdateProfileController extends Controller
{
    public function update()
    {
        request()->validate([
            'address' => 'required',
            'barangay' => 'required'
        ]);
        $user = auth()->user();
        $user->address =  request('address') .' '. request('barangay');
        $user->city = Str::title(request('city'));
        $user->province = Str::title(request('province'));
        $user->save();

        return response()->json([
            'message' => 'Successfully updated'
        ], Response::HTTP_ACCEPTED);
    }
}
