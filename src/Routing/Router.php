<?php

namespace NightFury\RestApi\Routing;

use Exception;

class Router
{
    public $api;
    public $namespace  = 'api';
    public $prefix     = '/';
    public $attributes = [];
    public $routes     = [];

    /**
     * An alias for calling the group method, allows a more fluent API
     * for registering a new API version group with optional
     * attributes and a required callback.
     *
     * This method can be called without the third parameter, however,
     * the callback should always be the last parameter.
     *
     * @param string         $version
     * @param array|callable $second
     * @param callable       $third
     *
     * @return void
     */
    public function version($version, $second, $third = null)
    {
        if (func_num_args() == 2) {
            list($version, $callback, $attributes) = array_merge(func_get_args(), [[]]);
        } else {
            list($version, $attributes, $callback) = func_get_args();
        }

        $attributes = array_merge($attributes, ['version' => $version]);

        $this->group($attributes, $callback);
    }

    /**
     * Create a new route group.
     *
     * @param array    $attributes
     * @param callable $callback
     *
     * @return void
     */
    public function group(array $attributes, $callback)
    {
        if (!isset($attributes['version'])) {
            throw new Exception('A version is required for an API group definition.');
        } else {
            $attributes['version'] = (array) $attributes['version'];
        }

        if ((!isset($attributes['prefix']) || empty($attributes['prefix'])) && isset($this->prefix)) {
            $attributes['prefix'] = $this->prefix;
        }

        $this->attributes = $attributes;

        call_user_func($callback, $this);
    }

    /**
     * Create a new GET route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function get($uri, $action)
    {
        return $this->addRoute(['GET', 'HEAD'], $uri, $action);
    }

    /**
     * Create a new POST route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function post($uri, $action)
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * Create a new PUT route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function put($uri, $action)
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Create a new PATCH route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function patch($uri, $action)
    {
        return $this->addRoute('PATCH', $uri, $action);
    }

    /**
     * Create a new DELETE route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function delete($uri, $action)
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Create a new OPTIONS route.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function options($uri, $action)
    {
        return $this->addRoute('OPTIONS', $uri, $action);
    }

    /**
     * Create a new route that responding to all verbs.
     *
     * @param string                $uri
     * @param array|string|callable $action
     *
     * @return mixed
     */
    public function any($uri, $action)
    {
        $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE'];

        return $this->addRoute($verbs, $uri, $action);
    }

    /**
     * Register an array of resources.
     *
     * @param array $resources
     *
     * @return void
     */
    public function resources(array $resources)
    {
        foreach ($resources as $name => $resource) {
            $options = [];

            if (is_array($resource)) {
                list($resource, $options) = $resource;
            }

            $this->resource($name, $resource, $options);
        }
    }

    /**
     * Add a route to the routing adapter.
     *
     * @param string|array          $methods
     * @param string                $uri
     * @param string|array|callable $action
     *
     * @return mixed
     */
    public function addRoute($methods, $uri, $action)
    {
        if (!is_string($action)) {
            throw new Exception("Action should be a string", 1);
        }

        if (is_string($methods)) {
            $methods = [$methods];
        }

        foreach ($methods as $method) {
            $this->routes[] = [
                'namespace' => "{$this->namespace}/{$this->attributes['version'][0]}",
                'uri'       => preg_replace_callback('/\{([a-z0-9]+)?\}/', function ($match) {
                    return "(?P<{$match[1]}>.+)";
                }, $uri),
                'args'      => [
                    'methods'  => $method,
                    'callback' => $this->parseAction($action),
                ],
            ];
        }
    }

    public function parseAction($action)
    {
        list($controller, $method) = explode('@', $action);
        return [new $controller, $method];
    }
}
