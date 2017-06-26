<?php


namespace Khaos\Bench\Resource\Type\Map;

use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\TypeRepository;
use Khaos\Bench\Resource\Type\TypeValue;

class MapTypeValue implements TypeValue
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
}