<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services;

use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Models\SimpleBlacklistIp;
use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Models\SimpleWhitelistIp;

class DatabaseDataProvider implements Interfaces\DatabaseDataProviderInterface
{
    public function getWhitelist(): array
    {
        $whitelist = \VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Models\SimpleWhitelistIp::all();

        return $whitelist->pluck('ip')->toArray();
    }

    public function getBlacklist(): array
    {
        $blacklist = \VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Models\SimpleBlacklistIp::all();

        return $blacklist->pluck('ip')->toArray();
    }

    public function blockIp($ip)
    {
        if (!$this->isBlacklisted($ip)) {
            SimpleBlacklistIp::create(['ip' => $ip]);
        }
    }

    public function unblockIp($ip)
    {
        SimpleBlacklistIp::where('ip', $ip)->delete();
    }

    public function whitelistIp($ip)
    {
        if (!$this->isWhitelisted($ip)) {
            SimpleWhitelistIp::create(['ip' => $ip]);
        }
    }

    public function unwhitelistIp($ip)
    {
        SimpleWhitelistIp::where('ip', $ip)->delete();
    }

    public function blacklistIp($ip)
    {
        if (!$this->isBlacklisted($ip)) {
            SimpleBlacklistIp::create(['ip' => $ip]);
        }
    }

    public function unblacklistIp($ip)
    {
        SimpleBlacklistIp::where('ip', $ip)->delete();
    }

    public function isBlocked($ip)
    {
        return SimpleBlacklistIp::where('ip', $ip)->exists();
    }

    protected function isWhitelisted($ip)
    {
        return SimpleWhitelistIp::where('ip', $ip)->exists();
    }

    protected function isBlacklisted($ip)
    {
        return SimpleBlacklistIp::where('ip', $ip)->exists();
    }
}
