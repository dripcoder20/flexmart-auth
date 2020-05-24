<?php

namespace App\Http\Controllers;

use App\Otp;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class VerificationController extends Controller
{
	/**
	 * @var Otp
	 */
	private $otpService;
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var Request
	 */
	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->user = $this->getUser();
		$this->otpService = new Otp(Cache::get($this->user->mobile_number));
	}

	public function resend()
    {
		$this->otpService->send($this->user->mobile_number, function ($code) {
			// Temporary message
			return "Good day, please use $code as your verification code. Thank you";
		});

		return response()->json([
			'message' => "Verification was sent."
		], Response::HTTP_ACCEPTED);
    }

	private function getUser()
	{
		if ($user = auth()->user()) return $user;

		$user = User::where('mobile_number', Crypt::decrypt($this->request->input("secure")))
		            ->first();

		$this->validatePublicUser($user);

		return $user;
	}

	private function validatePublicUser( $user ) {
		$this->request->validate( [
			'secure' => function ( $attribute, $value, $fail ) use ($user) {
				if (!$user) $fail( "User was not valid or link was invalid. Please login your account" );
			},
			'identity' => function ($attribute, $value, $fail) use ($user) {
				if (Cache::get(optional($user)->mobile_number) !== Crypt::decrypt($value))
					$fail("Verification link is expired or invalid. Please login your account");
			}
		]);
	}
}
