<?php

namespace GuedesRouter;

class Route
{
    protected $path;

    protected $callback;

    protected $pattern;

    /**
     * @param string $path
     * @param array|string|\Closure $callback
     */
    public function __construct(string $path = '/', $callback)
    {
        $this->path = $path;
        $this->callback = $callback;
    }

    /**
     * @param string $uri
     * 
     * @return bool
     */
    public function match(string $uri)
    {
        return (bool) preg_match($this->getPattern(), $uri);
    }

    public function getPath() : string
    {
        return $this->path;
    }

    public function getPattern() : string
    {
        if ($this->pattern) {
            return $this->pattern;
        }

        $pattern = preg_replace('~{([^}]*)}~', "([^/]+)", $this->path);
        $this->pattern = "/^" . str_replace('/', '\/', $pattern) . "$/";
        return $this->pattern;
    }
}