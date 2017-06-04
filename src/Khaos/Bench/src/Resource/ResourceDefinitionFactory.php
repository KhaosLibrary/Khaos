<?php

namespace Khaos\Bench\Resource;

/**
 * Interface ResourceDefinitionFactory
 *
 * @package Khaos\Bench\Resource
 */
interface ResourceDefinitionFactory
{
    public function getType();

    /**
     * Create Resource Definition From Array
     *
     * @param array $definition
     *
     * @return ResourceDefinition
     */
    public function create(array $definition);
}