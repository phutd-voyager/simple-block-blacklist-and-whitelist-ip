<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->registerServices();
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/simple_block_blacklist_and_whitelist_ip.php' => config_path('simple_block_blacklist_and_whitelist_ip.php'),
        ], 'simple-block-blacklist-and-whitelist-ip');

        $this->registerMiddleware();
    }

    protected function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware('ip-filter-blacklist', Middleware\IPFilterBlacklistMiddleware::class);
        $this->app['router']->aliasMiddleware('ip-filter-whitelist', Middleware\IPFilterWhitelistMiddleware::class);
    }

    protected function registerServices()
    {
        $this->app->singleton(Services\Interfaces\IPFilterServiceInterface::class, Services\IPFilterService::class);
    }
}
