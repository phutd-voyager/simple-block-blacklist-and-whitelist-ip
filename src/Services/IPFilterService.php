<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services;

class IPFilterService implements Interfaces\IPFilterServiceInterface
{
    protected $whitelist = [];
    protected $blacklist = [];

    public function __construct()
    {
        $this->loadFromConfig();

        if (config('simple_block_blacklist_and_whitelist_ip.block_by') === 'database') {
            $this->loadFromDatabase();
        }
    }

    protected function loadFromConfig()
    {
        $this->whitelist = config('simple_block_blacklist_and_whitelist_ip.whitelist_ip', []);
        $this->blacklist = config('simple_block_blacklist_and_whitelist_ip.blacklist_ip', []);
    }

    protected function loadFromDatabase()
    {
        // Load from database
    }

    public function isWhitelisted($ip)
    {
        return in_array($ip, $this->whitelist);
    }

    public function isBlacklisted($ip)
    {
        return in_array($ip, $this->blacklist);
    }
}
