<?php

namespace Libraries;

use App\Events\UserHasRequestedVerification;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Otp
{
    private const PREFIX = "otp-";
    private const EXP = 300; // 5 minutes
    private $identifier;
    private $code;

    public function __construct($identifier = null)
    {
        $this->identifier = self::PREFIX . ($identifier ?: Str::orderedUuid());
        $this->code       = $this->initializeCode();
    }

    private function initializeCode()
    {
        if (Cache::has($this->identifier)) {
            return Cache::get($this->identifier);
        }

        $code = rand(111111, 999999);
        Cache::put($this->identifier, $code, self::EXP);

        return $code;
    }

    public function send(String $mobile, callable $message)
    {
        $msg = call_user_func($message, $this->code);

        // TODO: Add Sms sending logic
        File::put("otp.txt", $msg);
        return $msg;
    }

    public function sendEmail(User $user)
    {
        return event(new UserHasRequestedVerification($user, $this->code));
    }

    public function isValid($code)
    {
        return $this->code == $code;
    }

    public function getIdentifier()
    {
        return Str::of($this->identifier)->substr(4);
    }
}
