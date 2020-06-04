<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_verify_user_via_public_url()
    {
        $identifier = '2d0a9796-0b33-4093-8227-cd1ceb2fed17';
        Cache::put('otp-'.$identifier, "654321", 5);

        $user = factory(User::class)->create();
        Cache::put('verified-' . $user->mobile_number, $identifier, 5);

        $request = [
            'code'     => '654321',
            'token'    => Crypt::encrypt('verified-' . $user->mobile_number)
        ];

        $result = $this->post('api/verify', $request)
            ->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertNotEmpty($result->json('confirmation_token'));
    }

    /**
     * @test
     */
    public function should_return_error_if_verification_credential_was_invalid()
    {
        $request = [
            'code'  => Crypt::encrypt('code'),
            'token'    => Crypt::encrypt('token')
        ];

        $this->postJson('api/verify', $request)
            ->assertStatus(422)
            ->assertJson(['errors' => [ 'code'  => []]]);
    }

    /**
     * @test
     */
    public function it_should_resend_a_verification_code_via_public_url()
    {
        $this->withoutExceptionHandling();
        $identifier = '2d0a9796-0b33-4093-8227-cd1ceb2fed17';

        Cache::put('verified-09090909090', $identifier, 5);

        $request = [
            'token'    => Crypt::encrypt('verified-09090909090')
        ];

        $this->post('api/resend-verification', $request)
            ->assertStatus(Response::HTTP_ACCEPTED);
    }

    /**
     * @test
     */
    public function it_should_see_mobile_number_in_verification()
    {
        $token = Crypt::encrypt('verified-+639154563216');
        $this->get("verify?token=$token")->assertSee('+639154563216');
    }
}
