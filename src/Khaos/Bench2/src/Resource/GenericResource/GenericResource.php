<?php

namespace Khaos\Bench2\Resource\GenericResource;

use Exception;
use Khaos\Bench2\Expression;
use Khaos\Bench2\Resource\GenericResource\Field\ObjectField;
use Khaos\Bench2\Resource\Resource;

class GenericResource implements Resource
{
    private $root;

    protected $data;

    public function __construct(Expression $expression, $schema, $data)
    {
        $this->root = new ObjectField($expression, $this, $schema, $data);
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