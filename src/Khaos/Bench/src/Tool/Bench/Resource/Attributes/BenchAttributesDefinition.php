<?php

namespace Khaos\Bench\Tool\Bench\Resource\Attributes;

use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\TypeRepository;

class BenchAttributesDefinition implements Definition
{
    /**
     * @var array
     */
    private $_data;

    /**
     * @var TypeRepository
     */
    private $_type;

    /**
     * DynamicDefinition constructor.
     *
     * @param array         $data           Data which will make up this definition
     * @param TypeRepository  $typeResolver   Used to match and return values
     */
    public function __construct(array $data, TypeRepository $typeResolver)
    {
        $this->_data = $data;
        $this->_type = $typeResolver;
    }

    /**
     * @inheritdoc
     */
    public function __get($key)
    {
        return
            $this->{$key} = $this->_type->{BenchAttributesSchema::SCHEMA['map'][$key]['type']}->value(
                BenchAttributesSchema::SCHEMA['map'][$key],
                $this->_data[$key],
                $this
            );
    }

    public function getDefinition()
    {
        return $this->{'definition'};
    }

    public function getWorkingDirectory()
    {
        return $this->{'metadata'}->{'working-directory'};
    }

    public function getSourceFile()
    {
        return $this->{'metadata'}->{'source-file'};
    }

    /**
     * @inheritdoc
     */
    public function match($match)
    {
        return $this->_type->{BenchAttributesSchema::SCHEMA['type']}->match(BenchAttributesSchema::SCHEMA, $match, $this);
    }

    /**
     * @return string
     */
    public function export()
    {
        return $this->_type->{BenchAttributesSchema::SCHEMA['type']}->export(BenchAttributesSchema::SCHEMA, $this->_data);
    }
}