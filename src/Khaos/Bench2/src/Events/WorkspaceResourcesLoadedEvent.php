<?php

namespace Khaos\Bench2\Events;

use Khaos\Schema\SchemaInstanceRepository;
use Symfony\Component\EventDispatcher\Event;

class WorkspaceResourcesLoadedEvent extends Event
{
    const NAME = 'bench.resources.loaded';

    /**
     * @var SchemaInstanceRepository
     */
    private $resources;

    /**
     * WorkspaceResourcesLoaded constructor.
     *
     * @param SchemaInstanceRepository $resources
     */
    public function __construct(SchemaInstanceRepository $resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return SchemaInstanceRepository
     */
    public function getResources()
    {
        return $this->resources;
    }
}