<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Libraries\Otp;

class MobileValidationController extends Controller {
	private const CODE_EXPIRATION = 900; // 15 minutes
	private const KEY_PREFIX = 'verified-';

	public function store( Request $request ) {
		$request->validate( [ 'mobile_number' => 'required|unique:users,mobile_number|size:13' ] );

		$token = $this->handleOtp( request( 'mobile_number' ) );

		if ( $request->wantsJson() ) {
			return response()->json( [
				'message' => 'Mobile was validated',
				'token'   => $token,
			], Response::HTTP_CREATED );
		}

		return redirect( '/verify?token=' . $token );
	}

	private function handleOtp( $mobile ) {
		$otp = new Otp();

		$otp->send( $mobile, function ( $code ) {
			return "Good day, please use $code as your verification code. Thank you";
		} );

		Cache::put( self::KEY_PREFIX . $mobile, $otp->getIdentifier(), self::CODE_EXPIRATION );

		return Crypt::encrypt( self::KEY_PREFIX . $mobile );
	}
}