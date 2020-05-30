<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class RegistrationController extends Controller
{
	public function store(Request $request) {
		$this->validateRequest($request);

		$userCredentials = $request->all();
		$userCredentials['password'] = bcrypt($userCredentials['password']);
		$userCredentials['mobile_number'] =
			Cache::get(request('confirmation_token'));

		User::create($userCredentials);

		return response()->json( [
			'message' => 'User was successfully created',
		], Response::HTTP_CREATED );
	}

	private function validateRequest(Request $request) {
		$request->validate([
			'first_name'    => 'required',
			'last_name'     => 'required',
			// We'll use a simple password for now
			'password'      => 'required',
			'confirmation_token'         => [
				'required',
				function ($attribute, $value, $fail) {
					if (!Cache::get($value))
						$fail('Token was invalid. Please sign up and verify your account first');
				}
			]
		]);
	}
}
