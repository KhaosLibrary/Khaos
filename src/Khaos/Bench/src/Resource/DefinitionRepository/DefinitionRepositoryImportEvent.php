<?php

namespace Khaos\Bench\Resource\DefinitionRepository;

use Khaos\Bench\Resource\ResourceDefinition;
use Symfony\Component\EventDispatcher\Event;

class DefinitionRepositoryImportEvent extends Event
{
    const EVENT = 'resource.definition.repository.import';

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