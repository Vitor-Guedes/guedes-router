<?php

namespace GuedesRouter;

use Exception;

class Route
{
    protected $path;

    protected $callback;

    protected $pattern;

    protected $uri;

    protected $separator;

    /**
     * @param string $path
     * @param array|string|\Closure $callback
     */
    public function __construct(string $path = '/', $callback, string $separator = '@')
    {
        $this->path = $path;
        $this->callback = $callback;
        $this->separator = $separator;
    }

    /**
     * @param string $uri
     * 
     * @return bool
     */
    public function match(string $uri)
    {
        $match = (bool) preg_match($this->getPattern(), $uri);
        if ($match) {
            $this->uri = $uri;
        }
        return $match;
    }

    /**
     * Return path route
     * 
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Return pattern created from path 
     * 
     * @return string
     */
    public function getPattern() : string
    {
        if ($this->pattern) {
            return $this->pattern;
        }

        $pattern = preg_replace('~{([^}]*)}~', "([^/]+)", $this->path);
        $this->pattern = "/^" . str_replace('/', '\/', $pattern) . "$/";
        return $this->pattern;
    }

    /**
     * Resolve Requested uri
     */
    public function resolve()
    {
        $parameters = $this->resolveParameters();
        if (is_string($this->callback)) {
            return $this->resolveStringCallable($parameters);
        }

        if (is_array($this->callback)) {
            return $this->resolveArrayCallable($parameters);
        }

        if (is_callable($this->callback)) {
            return call_user_func_array($this->callback, $parameters);
        }

        throw new Exception('Não foi possivel resolver a rota.');
    }

    /**
     * @return array
     */
    public function resolveParameters() : array
    {
        $parameterPattern = "~\{\s*([a-zA-Z_][a-zA-Z0-9_-]*)\}~x";
        preg_match_all($parameterPattern, $this->path, $keys, PREG_SET_ORDER);

        $uri = explode("/", $this->uri);
        $path = explode("/", $this->path);

        $differences = array_diff($uri, $path);
        $differencesValues = array_values($differences);

        $parameters = [];
        foreach ($keys as $key) {
            $parameters[$key[1]] = array_shift($differencesValues);
        }
        return $parameters;
    }

    /**
     * @param array $parameters
     * 
     * @return mixed
     */
    protected function resolveStringCallable(array $parameters = [])
    {
        $callback = explode($this->separator, $this->callback);
        return $this->resolveArrayCallable($parameters, $callback);
    }

    /**
     * @param array $parameters
     * @param array $callable
     * 
     * @return mixed
     */
    public function resolveArrayCallable(array $parameters = [], array $callable = [])
    {
        if (!$callable) {
            $callable = $this->callback;
        }
        list($controller, $function) = $callable;
        if (class_exists($controller)) {
            $instance = new $controller();
            if (method_exists($instance, $function)) {
                return $instance->$function($parameters);
            }
            throw new Exception("Metodo não existe na Controller: {$controller}.");
        }
        throw new Exception("Controller: $controller não existe.");
    }
}