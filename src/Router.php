<?php

namespace Src;

class Router
{
    protected $routes;
    protected $groups;

    protected function addRoute(string $http, Route $route)
    {
        $this->routes[$http][] = $route;
        return $route;
    }

    public function get(string $path, $callback)
    {
        return $this->addRoute(__FUNCTION__, new Route($path, $callback));
    }

    public function post(string $path, $callback)
    {
        return $this->addRoute(__FUNCTION__, new Route($path, $callback));
    }

    public function group(string $prefix, \Closure $callback)
    {
        $this->groups[$prefix] = new GroupRouter($prefix, $callback);
        return $this->groups[$prefix];
    }

    public function getRoutes(string $http = null)
    {
        return (isset($this->routes[$http])) ? $this->routes[$http] : $this->routes;
    }

    protected function hasGroup()
    {
        return !empty($this->groups);
    }

    protected function getGroupRoutes()
    {
        return ($this->groups) ? $this->groups : [];
    }

    protected function mergeRoutes()
    {
        foreach ($this->getGroupRoutes() as $group) {
            if ($group->hasGroup()) {
                $group->mergeRoutes();
            }
            $groupRoutes = $group->getRoutes();
            $namespace = $group->getNamespace();
            foreach ($groupRoutes as $http => $routes) {
                foreach ($routes as $route) {
                    ($namespace)
                        ? $this->addRoute($http, $route)->setNamespace($namespace)
                            : $this->addRoute($http, $route);
                }
            }
        }
    }
}