<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Tests;

class BaseTest extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [\VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\ServiceProvider::class];
    }
}