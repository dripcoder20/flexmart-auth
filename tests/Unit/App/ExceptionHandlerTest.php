<?php

namespace Tests\Unit;

use App\Exceptions\Handler;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tests\TestCase;

class ExceptionHandlerTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_render_the_page_if_the_request_is_not_from_api()
    {
        $handler = resolve(Handler::class);
        $request = resolve(Request::class);
        $this->expectException(RouteNotFoundException::class);
        $result = $handler->render($request, new AuthenticationException('Unauthenticated'));
        /* $this->post('/auth/login', ['mobile_number' => $user->mobile_number, 'password' => '12345', 'device_name' => 'test-device'])->assertStatus(200); */
    }
}
