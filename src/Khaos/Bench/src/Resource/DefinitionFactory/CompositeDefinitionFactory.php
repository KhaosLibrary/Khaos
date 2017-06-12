<?php

namespace Khaos\Bench\Resource\DefinitionFactory;

use InvalidArgumentException;
use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;

class CompositeDefinitionFactory implements ResourceDefinitionFactory
{
    /**
     * @var ResourceDefinitionFactory[]
     */
    private $definitionFactories;

    /**
     * @param ResourceDefinitionFactory $definitionFactory
     */
    public function add(ResourceDefinitionFactory $definitionFactory)
    {
        $this->definitionFactories[$definitionFactory->getType()] = $definitionFactory;
    }

    /**
     * @param $definition
     *
     * @return mixed
     */
    public function create(array $definition)
    {
        if (!isset($this->definitionFactories[$definition['resource']]))
            throw new InvalidArgumentException("Resource of type '{$definition['resource']}' is not supported.");

        return $this->definitionFactories[$definition['resource']]->create($definition);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return '*';
    }
}