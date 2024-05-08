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

## Usage

- We have 2 new middleware with alias `ip-filter-whitelist` and `ip-filter-blacklist`


```bash
Route::get('/test', function () {
    return 'Whitelist route';
})->middleware(['ip-filter-whitelist']); // middleware(['ip-filter-blacklist']
```