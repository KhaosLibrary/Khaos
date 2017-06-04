<?php

namespace Khaos\Bench\Resource\DefinitionLoader;

use Khaos\Bench\Resource\ResourceDefinitionLoader;

class CompositeDefinitionLoader implements ResourceDefinitionLoader
{
    /**
     * @var ResourceDefinitionLoader[]
     */
    private $definitionLoaders = [];

    /**
     * Add ResourceDefinitionLoader
     *
     * @param ResourceDefinitionLoader $definitionLoader
     *
     * @return void
     */
    public function add(ResourceDefinitionLoader $definitionLoader)
    {
        $this->definitionLoaders[] = $definitionLoader;
    }

    /**
     * @inheritdoc
     */
    public function load($source)
    {
        foreach ($this->definitionLoaders as $definitionLoader)
            if ($definitions = $definitionLoader->load($source))
                return $definitions;

        return null;
    }
}
