<?php

namespace Khaos\Schema;

use Exception;

class InstanceFactoryCollection
{
    /**
     * @var InstanceFactory[]
     */
    private $instanceFactories = [];

    /**
     * @param string           $schema
     * @param InstanceFactory  $factory
     */
    public function add($schema, InstanceFactory $factory)
    {
        $this->instanceFactories[$schema] = $factory;
    }

    /**
     * @param array $schema
     * @param mixed $data
     */
    public function create($schema, $data)
    {
        return $this->{$schema}->create($schema, $data);
    }

    /**
     * @param string $schema
     *
     * @return InstanceFactory
     *
     * @throws Exception
     */
    public function __get($schema)
    {
        if (!isset($this->instanceFactories[$schema]))
            throw new Exception(sprintf("Schema '%s' not found.", $schema));

        return $this->{$schema} = $this->instanceFactories[$schema];
    }
}