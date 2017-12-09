<?php

use NF\Facades\App;
use NightFury\RestApi\Routing\Router;

$api = App::make(Router::class);

$api->version('v1', function ($api) {
    $api->get('test', 'App\Http\Controllers\TestController@test');
    $api->get('test/{id}', 'App\Http\Controllers\TestController@show');
    // ... more route goes here
});

return $api;
