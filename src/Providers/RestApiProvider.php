<?php

namespace NFApi\Providers;

use Illuminate\Support\ServiceProvider;
use NFApi\Console\PublishCommand;

class RestApiProvider extends ServiceProvider
{
    public function register()
    {
        $route_file = $this->app->appPath() . '/routes/api.php';
        if (file_exists($route_file)) {
            $api = require_once $route_file;

            $routes = $api->routes;

            add_action('rest_api_init', function () use ($routes) {
                foreach ($routes as $route) {
                    register_rest_route($route['namespace'], $route['uri'], $route['args']);
                }
            });

        }

    }

    public function registerCommand()
    {
        return [
            PublishCommand::class,
        ];
    }

}
