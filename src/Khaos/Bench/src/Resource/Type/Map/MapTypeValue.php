<?php


namespace Khaos\Bench\Resource\Type\Map;

use ArrayAccess;
use Aura\Di\Exception;
use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\TypeRepository;
use Khaos\Bench\Resource\Type\TypeValue;

class MapTypeValue implements ArrayAccess, TypeValue
{
    /**
     * @var
     */
    private $_data;

    /**
     * @var array
     */
    private $_schema;

    /**
     * @var TypeRepository
     */
    private $_type;
    /**
     * @var Definition
     */
    private $_definition;

    /**
     * MapValue constructor.
     *
     * @param array           $schema
     * @param array           $data
     * @param TypeRepository  $typeRepository
     * @param Definition      $definition
     */
    public function __construct(array $schema, array $data, TypeRepository $typeRepository, Definition $definition)
    {
        $this->_data       = $data;
        $this->_schema     = $schema;
        $this->_type       = $typeRepository;
        $this->_definition = $definition;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return
            $this->{$key} = $this->_type->{$this->_schema['map'][$key]['type']}->value
            (
                $this->_schema['map'][$key],
                $this->_data[$key],
                $this->_definition
            );
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
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
        throw new Exception("Not Implemented");
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        throw new Exception("Not Implemented");
    }
}