<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services;

use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\Interfaces\DataProviderInterface;

class IPFilterService implements Interfaces\IPFilterServiceInterface
{
    protected $whitelist = [];
    protected $blacklist = [];
    protected $dataProvider;

    public function __construct(DataProviderInterface $dataProvider)
    {
        $this->whitelist = $dataProvider->getWhitelist();
        $this->blacklist = $dataProvider->getBlacklist();

        $this->dataProvider = $dataProvider;
    }

    public function isWhitelisted($ip)
    {
        return in_array($ip, $this->whitelist);
    }

    public function isBlacklisted($ip)
    {
        return in_array($ip, $this->blacklist);
    }

    public function blockIp($ip)
    {
        $this->checkProvider();

        $this->dataProvider->blockIp($ip);
        $this->blacklist[] = $ip;
    }

    public function unblockIp($ip)
    {
        $this->checkProvider();

        $this->dataProvider->unblockIp($ip);
        $this->blacklist = array_diff($this->blacklist, [$ip]);
    }

    public function whitelistIp($ip)
    {
        $this->checkProvider();

        $this->dataProvider->whitelistIp($ip);
        $this->whitelist[] = $ip;
    }

    public function unwhitelistIp($ip)
    {
        $this->checkProvider();

        $this->dataProvider->unwhitelistIp($ip);
        $this->whitelist = array_diff($this->whitelist, [$ip]);

    }

    public function blacklistIp($ip)
    {
        $this->checkProvider();

        $this->dataProvider->blacklistIp($ip);
        $this->blacklist[] = $ip;
    }

    public function unblacklistIp($ip)
    {
        $this->checkProvider();

        $this->dataProvider->unblacklistIp($ip);
        $this->blacklist = array_diff($this->blacklist, [$ip]);
    }

    public function isBlocked($ip)
    {
        $this->checkProvider();

        return $this->dataProvider->isBlocked($ip);
    }

    private function checkProvider()
    {
        if (!($this->dataProvider instanceof Interfaces\DatabaseDataProviderInterface)) {
            throw new \Exception('This method is only available for database data provider');
        }
    }
}
