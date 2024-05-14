<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Tests;

use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\IPFilterService;
use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\Interfaces\DataProviderInterface;
use VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\Services\Interfaces\DatabaseDataProviderInterface;

class IPFilterServiceTest extends BaseTest
{
    public function testIsWhitelisted()
    {
        $whitelist = ['192.168.1.1', '192.168.1.2'];
        $dataProvider = $this->createMock(DataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn($whitelist);
        $dataProvider->method('getBlacklist')->willReturn([]);

        $service = new IPFilterService($dataProvider);

        $this->assertTrue($service->isWhitelisted('192.168.1.1'));
        $this->assertFalse($service->isWhitelisted('192.168.1.3'));
    }

    public function testIsBlacklisted()
    {
        $blacklist = ['192.168.1.3', '192.168.1.4'];
        $dataProvider = $this->createMock(DataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn([]);
        $dataProvider->method('getBlacklist')->willReturn($blacklist);

        $service = new IPFilterService($dataProvider);

        $this->assertTrue($service->isBlacklisted('192.168.1.3'));
        $this->assertFalse($service->isBlacklisted('192.168.1.1'));
    }

    public function testBlockIp()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->expects($this->once())->method('blockIp')->with('192.168.1.5');

        $service = new IPFilterService($dataProvider);
        $service->blockIp('192.168.1.5');
    }

    public function testUnblockIp()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->expects($this->once())->method('unblockIp')->with('192.168.1.5');

        $service = new IPFilterService($dataProvider);
        $service->unblockIp('192.168.1.5');
    }

    public function testWhitelistIp()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->expects($this->once())->method('whitelistIp')->with('192.168.1.6');

        $service = new IPFilterService($dataProvider);
        $service->whitelistIp('192.168.1.6');
    }

    public function testUnwhitelistIp()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->expects($this->once())->method('unwhitelistIp')->with('192.168.1.6');

        $service = new IPFilterService($dataProvider);
        $service->unwhitelistIp('192.168.1.6');
    }

    public function testBlacklistIp()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->expects($this->once())->method('blacklistIp')->with('192.168.1.7');

        $service = new IPFilterService($dataProvider);
        $service->blacklistIp('192.168.1.7');
    }

    public function testUnblacklistIp()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->expects($this->once())->method('unblacklistIp')->with('192.168.1.7');

        $service = new IPFilterService($dataProvider);
        $service->unblacklistIp('192.168.1.7');
    }

    public function testIsBlocked()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->method('isBlocked')->willReturnMap([
            ['192.168.1.8', true],
            ['192.168.1.9', false],
        ]);

        $service = new IPFilterService($dataProvider);

        $this->assertTrue($service->isBlocked('192.168.1.8'));
        $this->assertFalse($service->isBlocked('192.168.1.9'));
    }

    public function testIsWhitelistedWithEmptyList()
    {
        $dataProvider = $this->createMock(DataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn([]);
        $dataProvider->method('getBlacklist')->willReturn([]);

        $service = new IPFilterService($dataProvider);

        $this->assertFalse($service->isWhitelisted('192.168.1.1'));
    }

    public function testIsBlacklistedWithEmptyList()
    {
        $dataProvider = $this->createMock(DataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn([]);
        $dataProvider->method('getBlacklist')->willReturn([]);

        $service = new IPFilterService($dataProvider);

        $this->assertFalse($service->isBlacklisted('192.168.1.3'));
    }

    public function testIsWhitelistedWithMultipleIPs()
    {
        $whitelist = ['192.168.1.1', '192.168.1.2', '192.168.1.3'];
        $dataProvider = $this->createMock(DataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn($whitelist);
        $dataProvider->method('getBlacklist')->willReturn([]);

        $service = new IPFilterService($dataProvider);

        $this->assertTrue($service->isWhitelisted('192.168.1.1'));
        $this->assertTrue($service->isWhitelisted('192.168.1.2'));
        $this->assertTrue($service->isWhitelisted('192.168.1.3'));
        $this->assertFalse($service->isWhitelisted('192.168.1.4'));
    }

    public function testIsBlacklistedWithMultipleIPs()
    {
        $blacklist = ['192.168.1.4', '192.168.1.5', '192.168.1.6'];
        $dataProvider = $this->createMock(DataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn([]);
        $dataProvider->method('getBlacklist')->willReturn($blacklist);

        $service = new IPFilterService($dataProvider);

        $this->assertTrue($service->isBlacklisted('192.168.1.4'));
        $this->assertTrue($service->isBlacklisted('192.168.1.5'));
        $this->assertTrue($service->isBlacklisted('192.168.1.6'));
        $this->assertFalse($service->isBlacklisted('192.168.1.7'));
    }

    public function testBlockIpPersists()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn([]);
        $dataProvider->method('getBlacklist')->willReturn([]);
        $dataProvider->expects($this->once())->method('blockIp')->with('192.168.1.8');

        $service = new IPFilterService($dataProvider);
        $service->blockIp('192.168.1.8');

        $this->assertTrue($service->isBlacklisted('192.168.1.8'));
    }

    public function testUnblockIpPersists()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn([]);
        $dataProvider->method('getBlacklist')->willReturn(['192.168.1.8']);
        $dataProvider->expects($this->once())->method('unblockIp')->with('192.168.1.8');

        $service = new IPFilterService($dataProvider);
        $service->unblockIp('192.168.1.8');

        $this->assertFalse($service->isBlacklisted('192.168.1.8'));
    }

    public function testWhitelistIpPersists()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn([]);
        $dataProvider->method('getBlacklist')->willReturn([]);
        $dataProvider->expects($this->once())->method('whitelistIp')->with('192.168.1.9');

        $service = new IPFilterService($dataProvider);
        $service->whitelistIp('192.168.1.9');

        $this->assertTrue($service->isWhitelisted('192.168.1.9'));
    }

    public function testUnwhitelistIpPersists()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn(['192.168.1.9']);
        $dataProvider->method('getBlacklist')->willReturn([]);
        $dataProvider->expects($this->once())->method('unwhitelistIp')->with('192.168.1.9');

        $service = new IPFilterService($dataProvider);
        $service->unwhitelistIp('192.168.1.9');

        $this->assertFalse($service->isWhitelisted('192.168.1.9'));
    }

    public function testBlacklistIpPersists()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn([]);
        $dataProvider->method('getBlacklist')->willReturn([]);
        $dataProvider->expects($this->once())->method('blacklistIp')->with('192.168.1.10');

        $service = new IPFilterService($dataProvider);
        $service->blacklistIp('192.168.1.10');

        $this->assertTrue($service->isBlacklisted('192.168.1.10'));
    }

    public function testUnblacklistIpPersists()
    {
        $dataProvider = $this->createMock(DatabaseDataProviderInterface::class);
        $dataProvider->method('getWhitelist')->willReturn([]);
        $dataProvider->method('getBlacklist')->willReturn(['192.168.1.10']);
        $dataProvider->expects($this->once())->method('unblacklistIp')->with('192.168.1.10');

        $service = new IPFilterService($dataProvider);
        $service->unblacklistIp('192.168.1.10');

        $this->assertFalse($service->isBlacklisted('192.168.1.10'));
    }
}
