<?php

namespace Khaos\Bench2\Resource;

use Exception;
use Khaos\Bench2\Expression;

class GenericResource
{
    private $root;

    protected $data;

    public function __construct(Expression $expression, $schema, $data)
    {
        $this->root = new Field($expression, $this, $schema, $data);
        $this->data = $data;
    }

    public function __get($field)
    {
        return $this->{$field} = $this->root->{$field};
    }

    public function __isset($name)
    {
        return isset($this->data->{$name});
    }

    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) !== 'get')
            throw new Exception();

        $field = lcfirst(substr($name, 3));

        return $this->{$field};
    }
}