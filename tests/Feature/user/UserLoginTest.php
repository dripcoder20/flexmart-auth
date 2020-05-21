<?php

namespace Tests\Feature\user;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
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
    }

    /**
     * @test
     */
    public function it_should_show_login_page()
    {
        $this->get('/login')->assertViewIs('login');
    }

    /**
     * @test
     */
    public function it_should_redirect_to_login_if_user_is_unauthenticated()
    {

        $this->get('/')->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function it_should_redirect_to_home_if_user_is_already_authenticated()
    {
        $this->actingAs($this->user);

        $this->get('/login')->assertRedirect('/');
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
        $this->post('/api/login', ['mobile_number' => $this->user->mobile_number, 'password' => '123456', 'device_name' => 'test-device'])->assertStatus(200);

        $this->assertEquals($this->user->mobile_number, auth()->user()->mobile_number);
    }


    /**
     * @test
     */
    public function it_will_return_error_if_user_provided_invalid_credentials()
    {
        $this->withHeader('Content-Type', 'application/json');
        $this->withHeader('X-Requested-With', 'XMLHttpRequest');
        $this->withHeader('Accept', 'application/json');
        $this->post(
            '/api/login',
            [
                'mobile_number' => 'invalid-number',
                'password' => '123456',
                'device_name' => 'sample-device'
            ]
        )
            ->assertStatus(422);
    }
}
