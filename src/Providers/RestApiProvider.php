<?php

namespace NFApi\Providers;

use App\Widgets\SampleWidget;
use Illuminate\Support\ServiceProvider;
use NF\Facades\App;

class RestApiProvider extends ServiceProvider
{
    public $listen = [
        SampleWidget::class,
    ];

    public function register()
    {
        $route_file = App::appPath() . '/routes/api.php';
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
            \NF\RestApi\Console\PublishCommand::class,
        ];
    }

}
