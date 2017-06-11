<?php

namespace Khaos\Bench\Resource\DefinitionLoader;

use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionLoader;

class GlobPatternDefinitionLoader implements ResourceDefinitionLoader
{
    /**
     * @var FileDefinitionLoader
     */
    private $fileDefinitionLoader;

    public function __construct(FileDefinitionLoader $fileDefinitionLoader)
    {
        $this->fileDefinitionLoader = $fileDefinitionLoader;
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
        $resources = [];

        foreach (glob($source) as $file)
            $resources = array_merge($resources, $this->fileDefinitionLoader->load($file));

        return $resources;
    }
}