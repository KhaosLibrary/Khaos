<?php

namespace Khaos\Bench2\Resource\GenericResource\Field;

use Exception;
use Khaos\Bench2\Expression;
use Khaos\Bench2\Resource\GenericResource\Field\ArrayField;
use Khaos\Bench2\Resource\GenericResource\Field\Field;

class ObjectField
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
     * @param $field
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function __get($field)
    {
        if (!isset($this->data->{$field}))
            throw new Exception();

        $data   = $this->data->{$field};
        $schema = $this->schema['properties'][$field] ?? [];

        if (is_object($data))
            return $this->{$field} = new self($this->expression, $this->resource, $schema, $data);

        if (is_array($data))
            return $this->{$field} = new ArrayField($this->expression, $this->resource, $schema, $data);

        if (is_scalar($data) && !isset($schema['expression']))
            return $this->{$field} = $data;

        return $this->expression->evaluate($data, ['self' => $this->resource]);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->{$name});
    }
}