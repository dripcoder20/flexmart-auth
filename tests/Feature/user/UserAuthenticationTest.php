<?php

namespace Tests\Feature\user;

use App\User;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    /**
     * @var Authenticable
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'password' => bcrypt('123456')
        ]);

        $this->withHeader('X-Requested-With', 'XMLHttpRequest');
    }
    /**
     * @test
     * */
    public function a_user_can_retrieve_a_sanctum_cookie()
    {

        $this->get('/sanctum/csrf-cookie')->assertCookie("XSRF-TOKEN");
    }

    /**
     * @test
     */
    public function it_can_login_user_via_spa_method()
    {
        $this->withoutExceptionHandling();
        $this->post('/auth/login', ['mobile_number' => $this->user->mobile_number, 'password' => '123456', 'device_name' => 'test-device'])->assertStatus(200);

        $this->get('/api/auth/user')->assertSee($this->user->mobile_number);
    }

    /**
     * @test
     */
    public function it_can_login_user_via_api_token_method()
    {

        $this->post(
            '/api/auth/login',
            [
                'mobile_number' => $this->user->mobile_number,
                'password' => '123456',
                'device_name' => 'sample-device'
            ]
        )
            ->assertStatus(200)
            ->assertSee('token');
    }


    /**
     * @test
     */
    public function it_can_get_user_data_via_api_token()
    {

        $response = $this->post(
            '/api/auth/login',
            [
                'mobile_number' => $this->user->mobile_number,
                'password' => '123456',
                'device_name' => 'sample-device'
            ]
        )
            ->assertStatus(200);

        $token = $response->json()['token'];

        $this->withHeader("Authorization", "Bearer " . $token);
        $this->get('/api/auth/user')->assertStatus(200)->assertSee($this->user->first_name);
    }

    /**
     * @test
     */
    public function it_will_return_error_if_user_provided_invalid_credentials()
    {
        $this->post(
            '/api/auth/login',
            [
                'mobile_number' => 'invalid-number',
                'password' => '123456',
                'device_name' => 'sample-device'
            ]
        )
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_should_logout_user()
    {
        $this->withoutExceptionHandling();
        $this->post(
            '/auth/login',
            [
                'mobile_number' => $this->user->mobile_number,
                'password' => '123456',
                'device_name' => 'sample-device'
            ]
        )->assertStatus(200);

        $this->assertEquals($this->user->id, auth()->user()->id);

        $this->delete('/api/auth/logout')->assertStatus(202)->assertCookieMissing('xsrf-token');
    }
}
