<?php

namespace Khaos\Bench\Resource\DefinitionRepository;

use InvalidArgumentException;
use Khaos\Bench\Resource\DefinitionRepository\Event\ResourceDefinitionImportedEvent;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DefinitionRepository implements ResourceDefinitionRepository
{
    private $resourceDefinitions = [];

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var ResourceDefinitionLoader
     */
    private $definitionLoader;

    /**
     * ResourceRepository constructor.
     *
     * @param EventDispatcher $eventDispatcher
     * @param ResourceDefinitionLoader $definitionLoader
     */
    public function __construct(EventDispatcher $eventDispatcher, ResourceDefinitionLoader $definitionLoader)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->definitionLoader = $definitionLoader;
    }

    /**
     * @param mixed $source
     *
     * TODO: remove definition loader behaviour
     */
    public function import($source)
    {
        if ($source instanceof ResourceDefinition)
        {
            $this->importResourceDefinition($source);
        }
        else
        {
            $definitions = $this->definitionLoader->load($source);

            if ($definitions == null)
                throw new InvalidArgumentException("Unsupported definition source encountered.");

            foreach ($definitions as $definition)
                $this->importResourceDefinition($definition);
        }
    }

    public function findById(string $id)
    {
        return $this->resourceDefinitions[$id] ?? null;
    }

    /**
     * @param string $type
     *
     * @return ResourceDefinition[]
     *
     * TODO: Look into performance
     */
    public function findByType(string $type)
    {
        return array_filter($this->resourceDefinitions, function(ResourceDefinition $resource) use($type)
        {
            return $resource->getType() == $type;
        });
    }

    private function importResourceDefinition(ResourceDefinition $definition)
    {
        $id = $definition->getId();

        if (isset($this->resourceDefinitions[$id]))
            throw new InvalidArgumentException("A resource definition with the ID '{$id}' has already been imported.");

        $this->resourceDefinitions[$definition->getId()] = $definition;

        $event = new ResourceDefinitionImportedEvent($definition);
        $this->eventDispatcher->dispatch(ResourceDefinitionImportedEvent::NAME, $event);
        $this->eventDispatcher->dispatch(ResourceDefinitionImportedEvent::NAME.'::'.$definition->getType(), $event);
    }

    public function query(callable $matcher)
    {
        $results = [];

        foreach ($this->resourceDefinitions as $resourceDefinition)
            if ($matcher($resourceDefinition) == true)
                $results[] = $resourceDefinition;

        return $results;
    }
}