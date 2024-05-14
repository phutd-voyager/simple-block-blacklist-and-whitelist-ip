<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services;

class ConfigDataProvider implements Interfaces\DataProviderInterface
{
    public function getWhitelist(): array
    {
        return config('simple_block_blacklist_and_whitelist_ip.whitelist_ip', []);
    }

    public function getBlacklist(): array
    {
        return config('simple_block_blacklist_and_whitelist_ip.blacklist_ip', []);
    }
}
