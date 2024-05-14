<?php

namespace VoyagerInc\SimpleBlockBlacklistAndWhiteListIp;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    private const BLOCK_BY_DATABASE = 'database';

    public function register()
    {
        $this->registerServices();
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/simple_block_blacklist_and_whitelist_ip.php' => config_path('simple_block_blacklist_and_whitelist_ip.php'),
        ], 'simple-block-blacklist-and-whitelist-ip-config');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'simple-block-blacklist-and-whitelist-ip-migrations');

        $this->registerMiddleware();
    }

    protected function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware('ip-filter-blacklist', Middleware\IPFilterBlacklistMiddleware::class);
        $this->app['router']->aliasMiddleware('ip-filter-whitelist', Middleware\IPFilterWhitelistMiddleware::class);
    }

    protected function registerServices()
    {
        $this->app->singleton(Services\Interfaces\DataProviderInterface::class, function () {
            if (strtolower(config('simple_block_blacklist_and_whitelist_ip.block_by')) === self::BLOCK_BY_DATABASE) {
                return new Services\DatabaseDataProvider();
            }

            return new Services\ConfigDataProvider();
        });

        $this->app->singleton(Services\Interfaces\IPFilterServiceInterface::class, function ($app) {
            return new Services\IPFilterService($app->make(Services\Interfaces\DataProviderInterface::class));
        });
    }
}
