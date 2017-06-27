<?php

namespace Khaos\Bench\Tool\Docker\Resource\Registry;

use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\TypeRepository;


/**
 * Class DockerRegistryDefinition
 *
 * @package Khaos\Bench\Tool\Docker\Resource\Image
 */
class DockerRegistryDefinition implements Definition
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
     * @param array           $data             Data which will make up this definition
     * @param TypeRepository  $typeRepository   Used to match and return values
     */
    public function __construct(array $data, TypeRepository $typeRepository)
    {
        $this->_data = $data;
        $this->_type = $typeRepository;
    }

    public function getServer()
    {
        return $this->{'definition'}->{'server'};
    }

    public function getUsername()
    {
        return $this->{'definition'}->{'username'};
    }

    public function getPassword()
    {
        return $this->{'definition'}->{'password'};
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
    public function __get($key)
    {
        return
            $this->{$key} = $this->_type->{DockerRegistrySchema::SCHEMA['map'][$key]['type']}->value(
                DockerRegistrySchema::SCHEMA['map'][$key],
                $this->_data[$key],
                $this
            );
    }

    /**
     * @inheritdoc
     */
    public function match($match)
    {
        return $this->_type->{DockerRegistrySchema::SCHEMA['type']}->match(DockerRegistrySchema::SCHEMA, $match, $this);
    }

    /**
     * @return string
     */
    public function export()
    {
        return $this->_type->{DockerRegistrySchema::SCHEMA['type']}->export(DockerRegistrySchema::SCHEMA, $this->_data);
    }
}