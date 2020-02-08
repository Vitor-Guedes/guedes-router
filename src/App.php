<?php

namespace Src;

use Exception;

class App extends Router
{
    public function dispatch(string $http, string $uri)
    {
        $this->mergeRoutes();
        foreach ($this->getRoutes($http) as $route) {
            if ($route->match($uri)) {
                return $route->execute();
            }
        }
        throw new Exception('Error 404 - Not Founded');
    }
}