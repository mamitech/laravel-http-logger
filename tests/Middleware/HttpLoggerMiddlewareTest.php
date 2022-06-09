<?php

namespace Mamitech\LaravelHttpLogger\Test\Middleware;

use Illuminate\Http\Request;
use Mamitech\LaravelHttpLogger\Middleware\HttpLoggerMiddleware;

class HttpLoggerMiddlewareTest extends \Orchestra\Testbench\TestCase
{
    public function test_no_exception_thrown()
    {
        // Given we have a request
        $request = new Request();

        // when we pass the request to this middleware,
        // the response should contain 'Hello World'
        $response = (new HttpLoggerMiddleware())->handle($request, function ($_) { return 'ok'; });

        $this->assertStringContainsString('ok', $response);
    }
}