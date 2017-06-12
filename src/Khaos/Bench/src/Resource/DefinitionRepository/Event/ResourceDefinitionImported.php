<?php

namespace Khaos\Bench\Resource\DefinitionRepository\Event;

use Khaos\Bench\Resource\ResourceDefinition;
use Symfony\Component\EventDispatcher\Event;

class ResourceDefinitionImported extends Event
{
    const NAME = 'resource.definition.repository.import';

    /**
     * @var ResourceDefinition
     */
    private $resourceDefinition;

    public function __construct(ResourceDefinition $resourceDefinition)
    {
        $this->resourceDefinition = $resourceDefinition;
    }

    public function getResourceDefinition()
    {
        return $this->resourceDefinition;
    }
}