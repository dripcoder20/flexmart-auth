<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Libraries\Otp;

class RegistrationController extends Controller
{
	private const IDENTITY_VALIDITY = 720;
	protected $otpService;

	public function __construct( Otp $otpService)
	{
		$this->otpService = $otpService;
	}

	public function store(Request $request) {
		$this->validateRequest($request);

		$userCredentials = $request->all();
		$userCredentials['password'] = bcrypt($userCredentials['password']);

		User::create($userCredentials);

		List($token, $secret) = $this->handleOtp($userCredentials['mobile_number']);

		if ($request->wantsJson()) {
			return response()->json( [
				'message' => 'User was successfully created',
				'token'    => $token,
				'secret'   => $secret
			], Response::HTTP_CREATED );
		}

		return redirect('/verify?'. http_build_query([
			'token'    => $token,
			'secret'   => $secret
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

	private function handleOtp( $mobileNumber ) {

		$this->otpService->send($mobileNumber, function ($code) {
			// Temporary message
			return "Good day, please use $code as your verification code. Thank you";
		});

		Cache::put($mobileNumber, $this->otpService->getIdentifier(), self::IDENTITY_VALIDITY);

		return [Crypt::encrypt($mobileNumber), Crypt::encrypt($this->otpService->getIdentifier())];
	}
}
