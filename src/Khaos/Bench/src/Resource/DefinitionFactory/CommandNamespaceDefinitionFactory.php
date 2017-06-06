<?php

namespace Khaos\Bench\Resource\DefinitionFactory;

use Khaos\Bench\Resource\Definition\CommandNamespaceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;

class CommandNamespaceDefinitionFactory implements ResourceDefinitionFactory
{
    public function getType()
    {
        return CommandNamespaceDefinition::TYPE;
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
        return new CommandNamespaceDefinition($definition);
    }
}
