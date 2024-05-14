<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\Interfaces;

interface DataProviderInterface
{
    public function getWhitelist(): array;
    public function getBlacklist(): array;
}