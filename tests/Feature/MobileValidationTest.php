<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class MobileValidationTest extends TestCase
{
	/**
	 * @test
	 */
	public function it_should_respond_token_if_mobile_was_valid( )
	{
		$request = [
			'mobile_number' => '+639154563216'
		];
		$this->postJson('api/validate', $request)
			->assertStatus(201)
			->assertSee('token');
	}

	/**
	 * @test
	 */
	public function it_should_redirect_if_mobile_was_valid( )
	{
		$request = [
			'mobile_number' => '+639154563216'
		];
		$result = $this->post('api/validate', $request)
		     ->assertRedirect();
		$location = $result->headers->get('location');
		$this->assertStringContainsString('/verify', $location);
	}

	/**
	 * @test
	 */
	public function it_should_return_error_if_mobile_was_previously_registered( )
	{
		factory(User::class)->create([
			'mobile_number' => '+639154563216'
		]);
		$request = [
			'mobile_number' => '+639154563216'
		];
		$this->postJson('api/validate', $request)
			->assertStatus(422)
			->assertJson([
				'errors' => [ "mobile_number" => []]
			]);
	}
}
