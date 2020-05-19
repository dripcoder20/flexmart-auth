<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class RegistrationController extends Controller
{
	public function register(Request $request) {
		$this->validateRequest($request);

		$userCredentials = $request->all();
		$userCredentials['password'] = bcrypt($userCredentials['password']);

		User::create($userCredentials);

		// TODO: Add OTP logic here
		if ($request->wantsJson()) {
			return response()->json( [
				'message' => 'User was successfully created'
			], Response::HTTP_CREATED );
		}

		return Redirect::to('/verify');
	}

	private function validateRequest(Request $request) {
		$request->validate([
			'first_name'    => 'required',
			'last_name'     => 'required',
			'mobile_number' => 'required',
			// We'll use a simple password for now
			'password'      => 'required'
		]);
	}
}
