# Simple block blacklist and whitelist IP

[`PHP v8.2`](https://php.net)

[`Laravel v11.x`](https://github.com/laravel/laravel)

## Installation

```bash
composer require voyager-inc/simple-block-blacklist-and-whitelist-ip
```

- Publish provider
```bash
php artisan vendor:publish --provider="VoyagerInc\SimpleBlockBlacklistAndWhiteListIp\ServiceProvider"
```

Now you will see two migration files `create_simple_blacklist_ips_table.php` and `create_simple_whitelist_ips_table.php` in `database/migrations` folder

- Run migration
```bash
php artisan migrate
```

## Usage

- We have two new middleware with the aliases `ip-filter-whitelist` and `ip-filter-blacklist`
- The `ip-filter-whitelist` middleware allows access only from whitelisted IPs.
- The `ip-filter-blacklist` middleware always prevents access from blacklisted IPs.
- We can update whitelist and blacklist in config `simple_block_blacklist_and_whitelist_ip.php` file

```bash
<?php

return [
    'block_ip_enable' => true,

    'block_by' => 'config', // or database

    'whitelist_ip' => [
        //
    ],

    'blacklist_ip' => [
        //
    ],
];

```

- Example

```bash
Route::get('/test', function () {
    return 'Whitelist route';
})->middleware(['ip-filter-whitelist']); // middleware(['ip-filter-blacklist']
```