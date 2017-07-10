<?php

namespace Khaos\Schema;

use Exception;

use Khaos\Schema\Events\SchemaLoadedEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SchemaRepository
{
    /**
     * @var array
     */
    private $map = [];

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * SchemaRepository constructor.
     *
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();
    }

    /**
     * @param SchemaProvider $provider
     */
    public function addSchemaProvider(SchemaProvider $provider)
    {
        foreach ($provider->getAvailableSchemas() as $schema)
            $this->map[$schema] = $provider;
    }

    /**
     * @param $schema
     *
     * @return array
     */
    public function get($schema)
    {
        return $this->{$schema}->getSchema();
    }

    /**
     * @param string $schema
     * @param mixed $data
     */
    public function createInstance($schema, $data)
    {
        return $this->{$schema}->getInstanceFactory()->create($data);
    }

    /**
     * @param string $name
     *
     * @return Schema
     *
     * @throws Exception
     */
    public function __get($name)
    {
        if (!isset($this->map[$name]))
            throw new Exception();

        $schema = ($this->map[$name] instanceof Schema) ? $this->map[$name] : $this->map[$name]->get($name);
        $event  = new SchemaLoadedEvent($schema);

        $this->eventDispatcher->dispatch(SchemaLoadedEvent::NAME, $event);
        $this->eventDispatcher->dispatch(SchemaLoadedEvent::NAME.':'.$schema->getName(), $event);

        return $this->{$name} = $schema;
    }

    /**
     * @param Schema $schema
     */
    public function addSchema(Schema $schema)
    {
        $this->map[$schema->getName()] = $schema;
    }
}