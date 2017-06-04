<?php

namespace Khaos\Bench\Resource\DefinitionLoader;

use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinitionLoader;

class ArrayDefinitionLoader implements ResourceDefinitionLoader
{
    /**
     * @var ResourceDefinitionFactory
     */
    private $definitionFactory;

    /**
     * ArrayDefinitionLoader constructor.
     *
     * @param ResourceDefinitionFactory $definitionFactory
     */
    public function __construct(ResourceDefinitionFactory $definitionFactory)
    {
        $this->definitionFactory = $definitionFactory;
    }

    /**
     * Load the given source of resource definitions
     *
     * @param mixed $source
     *
     * @return ResourceDefinition[]|null
     */
    public function load($source)
    {
        if (!is_array($source))
            return null;

        if (!isset($source[0]))
            $source = [$source];

        $definitions = [];

        foreach ($source as $document)
            $definitions[] = $this->definitionFactory->create($document);

        return $definitions;
    }
}
