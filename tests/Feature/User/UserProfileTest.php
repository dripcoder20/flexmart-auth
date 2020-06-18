<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;

class UserProfileTest extends TestCase
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
    public function it_should_show_profile_page()
    {
        $this->actingAs($this->user);
        $this->get('')->assertViewIs('profile');
    }

    /**
     * @test
     */
    public function it_can_get_user_data_via_api_token()
    {
        $response = $this->post(
            '/api/login',
            [
                'mobile_number' => $this->user->mobile_number,
                'password' => '123456',
                'device_name' => 'sample-device'
            ]
        )
            ->assertStatus(200);

        $token = $response->json()['token'];
        $this->withHeader('Content-type', 'application/json');
        $this->withHeader('Accept', 'application/json');
        $this->withHeader("Authorization", "Bearer " . $token);
        $this->get('/api/me')->assertStatus(200)->assertSee($this->user->first_name);
    }

    /**
     * @test
     */
    public function it_should_go_on_update_profile()
    {
        $this->actingAs($this->user);
        $this->get('/update-profile')->assertViewIs('update-profile');
    }

    /**
     * @test
     */
    public function should_update_profile_of_authenticated_user()
    {
        $request = [
            'address' => "102 molave",
            'barangay' => "duyan duyan",
            'city'  => 'QUEZON CITY',
            'province' => 'METRO MANILA'
        ];
        $this->actingAs($this->user)
            ->put('api/account', $request)
            ->assertStatus(202);

        $user = User::first();

        $this->assertEquals('102 molave duyan duyan', $user->address);
        $this->assertEquals('Quezon City', $user->city);
        $this->assertEquals('Metro Manila', $user->province);
    }

    /**
     * @test
     */
    public function should_response_error_if_invalid_request()
    {
        $request = [
            'barangay' => "duyan duyan",
            'city'  => 'QUEZON CITY',
            'province' => 'METRO MANILA'
        ];
        $this->actingAs($this->user)
             ->putJson('api/account', $request)
             ->assertStatus(422)
             ->assertJson([
                 'errors' => [
                     "address" => []
                ]
             ]);
    }
}
