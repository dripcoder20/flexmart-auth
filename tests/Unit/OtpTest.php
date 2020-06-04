<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Cache;
use Libraries\Otp;
use Tests\TestCase;

class OtpTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_send_correct_otp_code_message()
    {
        $identifier = '2d0a9796-0b33-4093-8227-cd1ceb2fed17';
        Cache::put('otp-'.$identifier, "123456", 5);

        $otp = new Otp($identifier);
        $message = $otp->send("09154563216", function ($code) {
            return "Please use code $code";
        });

        $this->assertEquals($message, 'Please use code 123456');
    }

    /**
     * @test
     */
    public function it_should_validate_if_code_was_valid_on_new_instance()
    {
        $otpIdentifier = app(Otp::class)->getIdentifier();

        $otpCode = Cache::get('otp-'.$otpIdentifier);

        $otpService = new Otp($otpIdentifier);

        $this->assertTrue($otpService->isValid($otpCode));
    }
}
