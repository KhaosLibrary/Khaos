<?php

namespace Khaos\Bench\Resource\Definition;

use IteratorAggregate;
use Khaos\Bench\Resource\Schema\SchemaRepository;
use Khaos\Bench\Resource\Type\TypeRepository;

interface DefinitionRepository extends IteratorAggregate
{
    /**
     * Add Definition
     *
     * @param array $data
     *
     * @return void
     */
    public function add(array $data);

    /**
     * Query Repository
     *
     * @param array $match
     *
     * @return Definition[]
     */
    public function query($match);

    /**
     * @return string
     */
    public function export();

    /**
     * Count of the number of definitions of a given schema that are loaded
     *
     * @param string $schema
     *
     * @return int
     */
    public function count($schema);

    /**
     * Get Definition
     *
     * @param string $key
     *
     * @return Definition
     */
    public function __get($key);

    /**
     * @return SchemaRepository
     */
    public function getSchemaRepository();

    /**
     * @return TypeRepository
     */
    public function getTypeRepository();
}