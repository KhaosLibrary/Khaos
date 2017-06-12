<?php

namespace Khaos\Bench\Resource;

/**
 * Interface ResourceDefinitionLoader
 *
 * Loaders are used to take various sources of resource definitions and return
 * them as concrete resource definition instances.
 *
 * Note: The load should always account for sources which may hold multiple definitions.
 *
 * @package Khaos\Bench\Resource
 */
interface ResourceDefinitionLoader
{
    /**
     * Load the given source of resource definitions
     *
     * @param mixed $source
     *
     * @return array|null
     */
    public function load($source);
}