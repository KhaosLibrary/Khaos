<?php

namespace Khaos\Bench\Tool\Bench\Resource\DefinitionFactory;

use Khaos\Bench\Tool\Bench\Resource\Definition\NamespaceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;

class NamespaceDefinitionFactory implements ResourceDefinitionFactory
{
    public function getType()
    {
        return NamespaceDefinition::TYPE;
    }

    /**
     * Create Resource Definition From Array
     *
     * @param array $definition
     *
     * @return ResourceDefinition
     */
    public function create(array $definition)
    {
        return new NamespaceDefinition($definition);
    }
}
