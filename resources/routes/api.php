<?php

use NF\Facades\App;
use NFApi\Routing\Router;

$api = App::make(Router::class);

$api->version('v1', function ($api) {
    $api->get('test', 'App\Http\Controllers\TestController@test');
    // ... more route goes here
});

return $api;
