<?php

namespace Khaos\Bench\Tool\Docker\Resource\DefinitionFactory;

use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use Khaos\Bench\Tool\Docker\Resource\Definition\DockerImageDefinition;

class DockerImageDefinitionFactory implements ResourceDefinitionFactory
{
    public function getType()
    {
        return DockerImageDefinition::TYPE;
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
        return new DockerImageDefinition($definition);
    }
}
