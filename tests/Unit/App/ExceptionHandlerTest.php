<?php

namespace Tests\Unit\App;

use App\Exceptions\Handler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Tests\TestCase;

class ExceptionHandlerTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_redirect_the_page_if_the_request_is_unauthenticated_and_not_from_api()
    {
        $handler = resolve(Handler::class);
        $request = resolve(Request::class);
        $result = $handler->render($request, new AuthenticationException('Unauthenticated'));
        $this->assertInstanceOf(RedirectResponse::class, $result);
    }
}
