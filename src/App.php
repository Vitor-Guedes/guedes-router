<?php

namespace GuedesRouter;

class App
{
    /**
     * Variable to simulate the input of a certain request method
     * 
     * @var string $method
     */
    protected string $method;

    /**
     * variable to simulate the input of a certain request uri
     * 
     * @var string $uri
     */
    protected string $uri;

    protected RouterCollection $routerCollection;
    
    public function __construct(string $method, string $uri)
    {
        $this->method = strtolower($method);
        $this->uri = $uri;

        $this->routerCollection = new RouterCollection();
    }

    public function run()
    {
        $collection = $this->routerCollection->getRoutes($this->method);
        foreach($collection as $route) {
            if ($route->match($this->uri)) {
                return $route->resolve();
            }
        }
        return "-";
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->routerCollection, $name)) {
            return call_user_func_array([
                    $this->routerCollection,
                    $name
                ], 
                $arguments
            );
        }
        return false;
    }
}