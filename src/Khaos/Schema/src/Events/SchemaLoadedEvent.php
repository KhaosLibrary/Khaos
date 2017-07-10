<?php

namespace Khaos\Schema\Events;

use Khaos\Schema\Schema;
use Symfony\Component\EventDispatcher\Event;

class SchemaLoadedEvent extends Event
{
    const NAME = 'schema.loaded';

    /**
     * @var Schema
     */
    private $schema;

    /**
     * SchemaLoadedEvent constructor.
     *
     * @param Schema $schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @return Schema
     */
    public function getSchema()
    {
        return $this->schema;
    }
}