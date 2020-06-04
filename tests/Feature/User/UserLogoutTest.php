<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;

class UserLogoutTest extends TestCase
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
    public function it_should_logout_user()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);
        $this->delete('/logout')->assertRedirect('login');
    }

    /**
     * @test
     */
    public function it_should_logout_user_via_api()
    {
        $this->actingAs($this->user);
        $this->delete('/api/logout')->assertStatus(202);
    }
}
