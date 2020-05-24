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
	public function it_should_resend_a_verification_code_to_authenticated_user( )
	{
		$user = factory(User::class)->create();

		$this->actingAs($user)->post('api/verify/resend')->assertStatus(Response::HTTP_ACCEPTED);
	}

	/**
	 * @test
	 */
	public function it_should_resend_a_verification_code_via_public_url( )
	{
		$identifier = '2d0a9796-0b33-4093-8227-cd1ceb2fed17';

		$user = factory(User::class)->create();
		Cache::put($user->mobile_number, $identifier, 5);

		$request = [
			'identity'  => Crypt::encrypt($identifier),
			'secure'    => Crypt::encrypt($user->mobile_number)
		];

		$this->post('api/verify/resend', $request)
			->assertStatus(Response::HTTP_ACCEPTED);
	}

	/**
	 * @test
	 */
	public function should_return_error_if_verification_credential_was_invalid( )
	{
		$request = [
			'identity'  => Crypt::encrypt('identity'),
			'secure'    => Crypt::encrypt('secure')
		];

		$this->postJson('api/verify/resend', $request)
			->assertStatus(422)
			->assertJson(['errors' => [
				'identity'  => [],
				'secure'    => []]
			]);
	}
}
