<?php


namespace Khaos\Bench\Resource\Schema;


interface SchemaRepository
{
    /**
     * Add Schema
     *
     * @param Schema $schema
     *
     * @return void
     */
    public function add(Schema $schema);

    /**
     * Get Schema
     *
     * @param string $key
     *
     * @return Schema
     */
    public function __get($key);
}