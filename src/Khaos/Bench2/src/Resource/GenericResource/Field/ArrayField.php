<?php

namespace Khaos\Bench2\Resource\GenericResource\Field;

use ArrayAccess;
use Exception;
use IteratorAggregate;
use Khaos\Bench2\Expression;

class ArrayField implements ArrayAccess, IteratorAggregate
{
    private $resource;
    private $schema;
    private $data;
    private $expression;

    /**
     * Field constructor.
     *
     * @param Expression $expression
     * @param $resource
     * @param $schema
     * @param $data
     */
    public function __construct(Expression $expression, $resource, $schema, $data)
    {
        $this->resource   = $resource;
        $this->schema     = $schema;
        $this->data       = $data;
        $this->expression = $expression;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception();
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        throw new Exception();
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        foreach (array_keys($this->data) as $index)
            yield $this->{$index};
    }


    /**
     * @param $index
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function __get($index)
    {
        if (!isset($this->data[$index]))
            throw new Exception();

        $data   = $this->data[$index];
        $schema = $this->schema['items'] ?? [];

        if (is_object($data))
            return $this->{$index} = new ObjectField($this->expression, $this->resource, $schema, $data);

        if (is_array($data))
            return $this->{$index} = new ArrayField($this->expression, $this->resource, $schema, $data);

        if (is_scalar($data) && !isset($schema['expression']))
            return $this->{$index} = $data;

        return $this->expression->evaluate($data, ['self' => $this->resource]);
    }
}