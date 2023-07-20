<?php

namespace GuedesRouter;

use GuedesRouter\Route;
use GuedesRouter\Traits\Verbs;

/**
 * @method object get(string $path, $closure)
 * @method object post(string $path, $closure)
 * @method object put(string $path, $closure)
 * @method object delete(string $path, $closure)
 */
class RouterCollection
{
    use Verbs;

    /**
     * Collection of routes
     * 
     * @var array $routes
     */
    protected array $routes;

    /**
     * Add a new route to collection
     * 
     * @param string $method get|post|put|delete
     * @param string $path
     * @param array|string|\Closure $callable
     * 
     * @return Route
     */
    protected function on(string $method, string $path, $callable) : Route
    {
        $route = new Route($path, $callable);
        $this->routes[$method][spl_object_hash($route)] = $route;
        return $route;
    }

    /**
     * Returns all routes or routes for a specific verb
     * 
     * @param string $method
     * 
     * @return array
     */
    public function getRoutes(string $method = '')
    {
        if (!empty($method)) {
            return $this->routes[$method] ?? [];
        }
        return  $this->routes ?? [];
    }
}