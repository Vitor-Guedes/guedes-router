<?php

namespace GuedesRouter\Traits;

trait Verbs
{
    /**
     * @param string $path
     * @param string|array|\Closure
     * 
     * @return object
     */
    public function get(string $path, $callable) : object
    {
        return $this->on(__FUNCTION__, $path, $callable);
    }

    /**
     * @param string $path
     * @param string|array|\Closure
     * 
     * @return object
     */
    public function post(string $path, $callable) : object
    {
        return $this->on(__FUNCTION__, $path, $callable);
    }

    /**
     * @param string $path
     * @param string|array|\Closure
     * 
     * @return object
     */
    public function put(string $path, $callable) : object
    {
        return $this->on(__FUNCTION__, $path, $callable);
    }

    /**
     * @param string $path
     * @param string|array|\Closure
     * 
     * @return object
     */
    public function delete(string $path, $callable) : object
    {
        return $this->on(__FUNCTION__, $path, $callable);
    }
}