<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\Interfaces;

interface IPFilterServiceInterface
{
    // public function isBlocked($ip);
    public function isWhitelisted($ip);
    public function isBlacklisted($ip);
    // public function blockIp($ip);
    // public function unblockIp($ip);
    // public function whitelistIp($ip);
    // public function unwhitelistIp($ip);
    // public function blacklistIp($ip);
    // public function unblacklistIp($ip);
}