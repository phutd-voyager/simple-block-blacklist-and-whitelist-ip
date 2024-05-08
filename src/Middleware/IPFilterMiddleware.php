<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Middleware;

use Closure;
use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\Interfaces\IPFilterServiceInterface;

class IPFilterMiddleware
{
    protected $ipFilterService;

    public function __construct(IPFilterServiceInterface $ipFilterService)
    {
        $this->ipFilterService = $ipFilterService;
    }

    public function handle($request, Closure $next)
    {
        $ip = $request->ip();

        if ($this->ipFilterService->isBlacklisted($ip)) {
            abort(403, 'Unauthorized');
        }

        if (!$this->ipFilterService->isWhitelisted($ip)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
