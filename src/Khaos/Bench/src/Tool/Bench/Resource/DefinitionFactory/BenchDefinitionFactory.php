<?php

namespace Khaos\Bench\Tool\Bench\Resource\DefinitionFactory;

use Khaos\Bench\Tool\Bench\Resource\Definition\BenchDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;

class BenchDefinitionFactory implements ResourceDefinitionFactory
{
    public function getType()
    {
        return BenchDefinition::TYPE;
    }

    public function create(array $definition)
    {
        return new BenchDefinition($definition);
    }
}

