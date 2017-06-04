<?php

namespace Khaos\Bench\Resource;

use InvalidArgumentException;

interface ResourceDefinitionRepository
{
    /**
     * Import Resource Definitions
     *
     * Note: The source may contain one or more definitions, this is handled
     * by the definition loaders.
     *
     * @param mixed $source
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function import($source);

    /**
     * Find By ID
     *
     * @param string $id
     *
     * @return ResourceDefinition|null
     */
    public function findById(string $id);

    /**
     * Find By Type
     *
     * @param string $type
     *
     * @return ResourceDefinition[]
     */
    public function findByType(string $type);
}