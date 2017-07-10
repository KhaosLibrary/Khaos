<?php

namespace Khaos\Schema;

interface SchemaProvider
{
    /**
     * @param string $schema
     *
     * @return Schema
     */
    public function get($schema);

    /**
     * @return string[]
     */
    public function getAvailableSchemas();
}