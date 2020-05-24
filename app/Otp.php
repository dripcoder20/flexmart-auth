<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Otp
{
	private const PREFIX = "otp-";
	private const TTL = 5;
	private $identifier;
	private $code;

	public function __construct($identifier = null)
    {
	    $this->identifier = self::PREFIX. ($identifier ?: Str::orderedUuid());
	    $this->code = $this->initializeCode();
    }

	private function initializeCode() {
		if (Cache::has($this->identifier))
			return Cache::get($this->identifier);

		$code = rand(111111,99999);
		Cache::put($this->identifier, $code, self::TTL);

		return $code;
	}

	public function send(String $mobile, Callable $message)
    {
    	$msg = call_user_func($message, $this->code);

    	// TODO: Add Sms sending logic
	    return $msg;
    }

	public function isValid($code)
    {
		return $this->code == $code;
    }

    public function getIdentifier() {
		return substr_replace($this->identifier, '', 0, 4);
    }
}
