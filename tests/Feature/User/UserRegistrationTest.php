<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    /**
     * @test
     */
    public function should_register_if_credential_was_valid()
    {
        $this->withoutExceptionHandling();
        $token = Hash::make('hash');
        Cache::put($token, '09090909090', 5);
        $request = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'confirmation_token' => $token,
            'password' => 'johndoe123'
        ];

        $this->postJson('api/register', $request)->assertStatus(201);

        $user = User::first();

        $this->assertEquals($user->first_name, $request['first_name']);
        $this->assertEquals($user->last_name, $request['last_name']);
        $this->assertEquals($user->mobile_number, '09090909090');
    }


    /**
     * @test
     */
    public function should_return_error_if_credential_was_invalid()
    {
        $request = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'password' => 'johndoe123',
            'confirmation_token' => 'token'
        ];

        $this->postJson('api/register', $request)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    "confirmation_token" => []
                ]
            ]);
    }


    /**
     * @test
     */
    public function it_should_set_mobile_number_and_redirect_session()
    {
        $this->get('signup')->assertRedirect('/mobile/validate');
        $this->assertEquals('unique:users,mobile_number', session('mobile:validation'));
        $this->assertEquals(config('app.url') . '/register', session('redirect_after'));
    }
}
