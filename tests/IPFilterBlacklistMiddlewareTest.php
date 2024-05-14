<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Middleware\IPFilterBlacklistMiddleware;
use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\Interfaces\IPFilterServiceInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IPFilterBlacklistMiddlewareTest extends BaseTest
{
    /**
     * Ensures that the middleware allows the request if the IP is not blacklisted.
     */
    public function testHandleAllowsWhitelistedIp()
    {
        $ipFilterService = $this->createMock(IPFilterServiceInterface::class);
        $ipFilterService->method('isBlacklisted')->willReturn(false);

        $middleware = new IPFilterBlacklistMiddleware($ipFilterService);

        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.1']);

        $response = new Response('OK', 200);

        $next = function ($req) use ($response) {
            return $response;
        };

        $result = $middleware->handle($request, $next);

        $this->assertEquals($response, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    /**
     * Ensures that the middleware blocks the request if the IP is blacklisted.
     */
    public function testHandleBlocksBlacklistedIp()
    {
        $ipFilterService = $this->createMock(IPFilterServiceInterface::class);
        $ipFilterService->method('isBlacklisted')->willReturn(true);

        $middleware = new IPFilterBlacklistMiddleware($ipFilterService);

        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.1']);

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

        $this->fail('Expected exception not thrown');
    }

    /**
     * Ensures that the middleware functions correctly with different HTTP methods (e.g., POST, PUT)
     */
    public function testHandleWithDifferentRequestMethods()
    {
        $ipFilterService = $this->createMock(IPFilterServiceInterface::class);
        $ipFilterService->method('isBlacklisted')->willReturn(false);

        $middleware = new IPFilterBlacklistMiddleware($ipFilterService);

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
     * Ensures that the middleware blocks blacklisted IPs for various HTTP methods
     */
    public function testHandleBlocksBlacklistedIpWithDifferentMethods()
    {
        $ipFilterService = $this->createMock(IPFilterServiceInterface::class);
        $ipFilterService->method('isBlacklisted')->willReturn(true);

        $middleware = new IPFilterBlacklistMiddleware($ipFilterService);

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
