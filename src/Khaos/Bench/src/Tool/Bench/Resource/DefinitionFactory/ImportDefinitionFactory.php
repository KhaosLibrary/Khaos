<?php

namespace Khaos\Bench\Tool\Bench\Resource\DefinitionFactory;

use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;

class ImportDefinitionFactory implements ResourceDefinitionFactory
{
    public function getType()
    {
        return ImportDefinition::TYPE;
    }

    public function create(array $definition)
    {
        return new ImportDefinition($definition);
    }
}