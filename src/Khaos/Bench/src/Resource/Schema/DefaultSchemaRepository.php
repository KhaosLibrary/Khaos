<?php

namespace Khaos\Bench\Resource\Schema;

use Exception;

class DefaultSchemaRepository implements SchemaRepository
{
    /**
     * @var Schema[]
     */
    private $schemas = [];

    /**
     * StandardSchemaRepository constructor.
     */
    public function __construct()
    {

    }

    /**
     * Add Schema
     *
     * @param Schema $schema
     *
     * @return void
     */
    public function add(Schema $schema)
    {
        $this->schemas[$schema->getName()] = $schema;
    }

    /**
     * Get Schema
     *
     * @param string $key
     *
     * @return Schema
     *
     * @throws Exception
     */
    public function __get($key)
    {
        if (!isset($this->schemas[$key]))
            throw new Exception("Schema '{$key}' could not be found.");

        return $this->{$key} = $this->schemas[$key];
    }
}