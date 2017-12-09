# Another way to working with Wordpress REST API 
 > It's an extension for our theme https://github.com/hieu-pv/nf-theme 
 
#### Installation
##### Step 1: Install Through Composer
```
composer require nf/restapi
```
##### Step 2: Add the Service Provider
> Open `config/app.php` and register the required service provider.

```php
  'providers'  => [
        // .... Others providers 
        \NFApi\Providers\RestApiProvider::class,
    ],
```
##### Step 3: Run publish command
> It will create 2 new folders `routes` and `app/Http` in your theme

```
php command restapi:publish
```
##### Step 4: Register a route
> You can register any route by update `routes/api.php`

```php
<?php

use NF\Facades\App;
use NFApi\Routing\Router;

$api = App::make(Router::class);

$api->version('v1', function ($api) {
    $api->get('test', 'App\Http\Controllers\TestController@test');
    $api->get('test/{id}', 'App\Http\Controllers\TestController@show');
    // ... more route goes here
});

return $api;

```
##### Step 5: Test your first API
```
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://{your_domain}/wp-json/api/v1/test
```
