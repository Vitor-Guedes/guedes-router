<?php

namespace Src;

use Exception;

class Route
{
    private $uri;
    private $path;
    private $data;
    private $callback;
    protected $namespace;

    public function __construct(string $path, $callback)
    {
        $this->path = $path; 
        $this->callback = $callback;
        $this->setPattern();
    }

    public function match(string $uri)
    {
        $this->uri = $uri;
        return (bool) preg_match($this->pattern, $uri);
    }

    public function execute()
    {
        $this->getDataRoute();
        if (is_callable($this->callback)) {
            return call_user_func_array($this->callback, [$this->data]);
        }
        $callback = explode(':', $this->callback);
        list($controller, $action) = $callback;
        $controller = ($this->namespace) ? "{$this->namespace}\\{$controller}" : $controller;
        if (class_exists($controller)) {
            $instance = new $controller();
            if (method_exists($instance, $action)) {
                return call_user_func_array([$instance, $action], $this->data);
            }
            throw new Exception("Não foi encontrado a função: $action na class $controller");
        }
        throw new Exception('Não Foi possivel instanciar a class: $controller');
    }

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    private function setPattern()
    {
        $pattern = preg_replace('~{([^}]*)}~', "([^/]+)", $this->path);
        $this->pattern = "/^" . str_replace('/', '\/', $pattern) . "$/";
    }

    private function getDataRoute()
    {
        preg_match_all("~\{\s*([a-zA-Z_][a-zA-Z0-9_-]*)\}~x", $this->path, $keys, PREG_SET_ORDER);
        $diff = array_values(array_diff(explode("/", $this->uri), explode("/", $this->path)));
        $this->data = [];
        foreach ($keys as $key) {
            $this->data[$key[1]] = array_shift($diff);
        }
    }
}