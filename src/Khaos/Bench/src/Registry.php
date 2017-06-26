<?php

namespace Khaos\Bench;

class Registry
{
    public function __construct()
    {

    }

    public function get($key)
    {
        return $this->{$key};
    }

    public function set($key, $value)
    {
        $this->{$key} = $value;
    }

    public function __get($key)
    {
        return $this->{$key} = null;
    }
}