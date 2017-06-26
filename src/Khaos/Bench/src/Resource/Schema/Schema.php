<?php

namespace Khaos\Bench\Resource\Schema;

use Khaos\Bench\Resource\Definition\Definition;

interface Schema
{
    /**
     * New definition from the given data applied to
     * this schema.
     *
     * @param array $data
     *
     * @return Definition
     */
    public function getDefinition(array $data);

    /**
     * Name of schema
     *
     * @return string
     */
    public function getName();
}