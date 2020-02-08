<?php

namespace Src;

class GroupRouter extends Router
{
    protected $prefix;
    protected $callback;
    protected $namespace;
    protected $parentGroupPrefix;

    public function __construct(string $prefix, \closure $callback)
    {
        $this->prefix = $prefix;
        $this->callback = $callback;
        call_user_func_array($callback, [$this]);
    }

    public function get(string $path, $callback)
    {
        $path = $this->getPrefixGroup($path);
        return parent::get($path, $callback);
    }

    public function post(string $path, $callback)
    {
        $path = $this->getPrefixGroup($path);
        return parent::post($path, $callback);
    }

    public function group(string $prefix, \Closure $callback)
    {
        $prefix = "{$this->getPrefix()}{$prefix}";
        return parent::group($prefix, $callback);
    }

    protected function getPrefix()
    {
        return $this->prefix;
    }

    private function getPrefixGroup(string $path)
    {
        return "{$this->prefix}{$path}";
    }

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function hasNamespace()
    {
        return !empty($this->namespace);
    }
}