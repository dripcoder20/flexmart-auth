<?php

namespace Tests\Feature\User;

use App\LoginLog;
use App\User;
use Carbon\Carbon;
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
        $this->withHeader('Accept', 'application/json');
        $this->post(
            '/api/login',
            [
                'mobile_number' => 'invalid-number',
                'password' => '123456',
                'device_name' => 'sample-device'
            ]
        )
            ->assertStatus(401);
    }

    /** @test */
    public function it_logs_users_login()
    {
        $this->withoutExceptionHandling();

        $this->post('/api/login', [
            'mobile_number' => $this->user->mobile_number,
            'password' => '123456',
            'device_name' => 'test_device'
        ]);
        $loggedIn = LoginLog::first();

        $this->assertDatabaseHas('user_login_history', [
            'user_id' => $this->user->id,
            'ip_address' => '127.0.0.1',
            'device_name' => 'test_device',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $this->assertEquals($this->user->mobile_number, $loggedIn->user->mobile_number);
        $this->assertEquals('test_device', $loggedIn->device_name);
    }
}
