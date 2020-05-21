<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
	use RefreshDatabase;
	/**
	 * @test
	 */
	public function should_register_if_credential_was_valid( )
	{
		$this->withoutExceptionHandling();
		$request = [
			'first_name' => 'John',
			'last_name'  => 'Doe',
			'mobile_number' => '09090909090',
			'password'   => 'johndoe123'
		];

		$this->postJson('api/register', $request)->assertStatus(201);

		$user = User::first();

		$this->assertEquals($user->first_name, $request['first_name']);
		$this->assertEquals($user->last_name, $request['last_name']);
	}

	/**
	 * @test
	 */
	public function should_redirect_to_verify_if_registration_sucessful( ) {
		$this->withoutExceptionHandling();
		$request = [
			'first_name' => 'John',
			'last_name'  => 'Doe',
			'mobile_number' => '09090909090',
			'password'   => 'johndoe123'
		];

		$this->post('api/register', $request)
		     ->assertStatus(302)
		     ->assertHeader('location', config('app.url') . '/verify');

		$user = User::first();
		$this->assertEquals($user->first_name, $request['first_name']);
		$this->assertEquals($user->last_name, $request['last_name']);
	}

	/**
	 * @test
	 */
	public function should_return_error_if_credential_was_invalid( )
	{
		$request = [
			'first_name' => 'John',
			'last_name'  => 'Doe',
			'password'   => 'johndoe123'
		];

		$this->postJson('api/register', $request)
		     ->assertStatus(422)
		     ->assertJson([
		     	'errors' => [
		     		"mobile_number" => []
		        ]
		     ]);
	}
}
