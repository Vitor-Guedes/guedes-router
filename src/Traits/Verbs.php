<?php

namespace GuedesRouter\Traits;

trait Verbs
{
    public function get(string $path, $callable) : object
    {
        return $this->on(__FUNCTION__, $path, $callable);
    }

    public function post(string $path, $callable) : object
    {
        return $this->on(__FUNCTION__, $path, $callable);
    }

    public function put(string $path, $callable) : object
    {
        return $this->on(__FUNCTION__, $path, $callable);
    }

    public function delete(string $path, $callable) : object
    {
        return $this->on(__FUNCTION__, $path, $callable);
    }
}