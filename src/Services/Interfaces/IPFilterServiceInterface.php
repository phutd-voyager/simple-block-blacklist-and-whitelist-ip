<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\Interfaces;

interface IPFilterServiceInterface
{
    public function isWhitelisted($ip);
    public function isBlacklisted($ip);

    // Methods for database actions
    public function blockIp($ip);
    public function unblockIp($ip);
    public function whitelistIp($ip);
    public function unwhitelistIp($ip);
    public function blacklistIp($ip);
    public function unblacklistIp($ip);
    public function isBlocked($ip);
}
