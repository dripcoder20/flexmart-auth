<?php

namespace Tests\Unit\middleware;

use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        Route::get('forbidden', function () {
        })->middleware('auth');
    }

    /**
     * @test
     */
    public function it_should_redirect_to_login_page_if_not_authenticated_and_through_browser()
    {
        $this->get('/forbidden')->assertStatus(302);
    }

    /**
     * @test
     */
    public function it_should_return_401_status_if_unathenticated_through_api()
    {
        $this->withHeader('Content-Type', 'application/json');
        $this->withHeader('Accept', 'application/json');
        $this->withHeader('X-Requested-With', 'XMLHttpRequest');
        $this->get('/forbidden')->assertStatus(401);
    }
}
