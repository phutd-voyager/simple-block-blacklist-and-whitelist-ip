<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Middleware\IPFilterWhitelistMiddleware;
use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\Interfaces\IPFilterServiceInterface;

class IPFilterWhitelistMiddlewareTest extends BaseTest
{
    /**
     * Ensures that the middleware allows the request if the IP is whitelisted
     */
    public function testHandleAllowsWhitelistedIp()
    {
        $ipFilterService = $this->createMock(IPFilterServiceInterface::class);
        $ipFilterService->method('isWhitelisted')->willReturn(true);

        $middleware = new IPFilterWhitelistMiddleware($ipFilterService);

        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.1']);

        $response = new Response('OK', 200);

        $next = function ($req) use ($response) {
            return $response;
        };

        $result = $middleware->handle($request, $next);

        $this->assertEquals($response, $result);
    }

    /**
     * Ensures that the middleware blocks the request if the IP is not whitelisted
     */
    public function testHandleBlocksNonWhitelistedIp()
    {
        $ipFilterService = $this->createMock(IPFilterServiceInterface::class);
        $ipFilterService->method('isWhitelisted')->willReturn(false);

        $middleware = new IPFilterWhitelistMiddleware($ipFilterService);

        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.2']);

        $next = function ($req) {
            return new Response('OK', 200);
        };

        try {
            $middleware->handle($request, $next);
        } catch (HttpException $e) {
            $this->assertEquals(403, $e->getStatusCode());
            $this->assertEquals('Unauthorized', $e->getMessage());
            return;
        }

        $this->fail('Expected HttpException was not thrown.');
    }

    /**
     * Ensures that the middleware can handle requests with invalid IP addresses gracefully
     */
    public function testHandleAllowsRequestWithInvalidIp()
    {
        $ipFilterService = $this->createMock(IPFilterServiceInterface::class);
        $ipFilterService->method('isWhitelisted')->willReturn(false);

        $middleware = new IPFilterWhitelistMiddleware($ipFilterService);

        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => 'invalid_ip']);

        $response = new Response('OK', 200);

        $next = function ($req) use ($response) {
            return $response;
        };

        try {
            $middleware->handle($request, $next);
        } catch (HttpException $e) {
            $this->assertEquals(403, $e->getStatusCode());
            $this->assertEquals('Unauthorized', $e->getMessage());
            return;
        }

        $this->fail('Expected HttpException was not thrown.');
    }

    /**
     * Ensures that the middleware functions correctly with different HTTP methods (e.g., POST, PUT)
     */
    public function testHandleWithDifferentRequestMethods()
    {
        $ipFilterService = $this->createMock(IPFilterServiceInterface::class);
        $ipFilterService->method('isWhitelisted')->willReturn(true);

        $middleware = new IPFilterWhitelistMiddleware($ipFilterService);

        $methods = ['POST', 'PUT', 'DELETE', 'PATCH'];
        foreach ($methods as $method) {
            $request = Request::create('/', $method, [], [], [], ['REMOTE_ADDR' => '192.168.1.3']);
            $response = new Response('OK', 200);

            $next = function ($req) use ($response) {
                return $response;
            };

            $result = $middleware->handle($request, $next);

            $this->assertEquals($response, $result);
        }
    }

    /**
     * Ensures that the middleware blocks non-whitelisted IPs for various HTTP methods
     */
    public function testHandleBlocksNonWhitelistedIpWithDifferentMethods()
    {
        $ipFilterService = $this->createMock(IPFilterServiceInterface::class);
        $ipFilterService->method('isWhitelisted')->willReturn(false);

        $middleware = new IPFilterWhitelistMiddleware($ipFilterService);

        $methods = ['POST', 'PUT', 'DELETE', 'PATCH'];
        foreach ($methods as $method) {
            $request = Request::create('/', $method, [], [], [], ['REMOTE_ADDR' => '192.168.1.4']);
            $next = function ($req) {
                return new Response('OK', 200);
            };

            try {
                $middleware->handle($request, $next);
            } catch (HttpException $e) {
                $this->assertEquals(403, $e->getStatusCode());
                $this->assertEquals('Unauthorized', $e->getMessage());
                continue;
            }

            $this->fail('Expected HttpException was not thrown for method ' . $method);
        }
    }
}
