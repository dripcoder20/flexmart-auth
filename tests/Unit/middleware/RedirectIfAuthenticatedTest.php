<?php

namespace Tests\Unit\middleware;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class RedirectIfAuthenticatedTest extends TestCase
{
    /**
     * @test
     */
    public function it_redirect_user_to_homepage_if_authenticated()
    {
        $middleware = resolve(RedirectIfAuthenticated::class);
        $request = resolve(Request::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $result = $middleware->handle($request, function () {
        });
        $this->assertInstanceOf(RedirectResponse::class, $result);
    }

    /**
     * @test
     */
    public function it_should_call_next_if_user_is_unauthenticated()
    {
        $middleware = resolve(RedirectIfAuthenticated::class);
        $request = resolve(Request::class);
        $user = factory(User::class)->create();
        $result = $middleware->handle($request, function ($request) {
            return $request;
        });
        $this->assertInstanceOf(Request::class, $result);
    }
}
