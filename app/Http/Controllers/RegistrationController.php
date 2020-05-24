<?php

namespace App\Http\Controllers;

use App\Otp;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class RegistrationController extends Controller
{
	private const IDENTITY_VALIDITY = 720;
	protected $otpService;

	public function __construct( Otp $otpService)
	{
		$this->otpService = $otpService;
	}

	public function register(Request $request) {
		$this->validateRequest($request);

		$userCredentials = $request->all();
		$userCredentials['password'] = bcrypt($userCredentials['password']);

		User::create($userCredentials);

		$this->otpService->send($userCredentials['mobile_number'], function ($code) {
			// Temporary message
			return "Good day, please use $code as your verification code. Thank you";
		});

		Cache::put($userCredentials['mobile_number'], $this->otpService->getIdentifier(),
			self::IDENTITY_VALIDITY);

		if ($request->wantsJson()) {
			return response()->json( [
				'message' => 'User was successfully created'
			], Response::HTTP_CREATED );
		}

		return Redirect::to('/verify?'. http_build_query([
				'identity' => Crypt::encrypt($this->otpService->getIdentifier()),
				'secure'   => Crypt::encrypt($userCredentials['mobile_number'])
			]));
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
