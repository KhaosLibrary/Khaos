<?php

namespace Khaos\Schema;

use Khaos\Cache\CacheItem;
use Khaos\Cache\CacheItemPool;
use Khaos\Schema\Keywords\SelfKeyword;

class SchemaInstanceRepository
{
    const INSTANCE_SCHEMA =
    [
        'description' => 'Top-level schema for the validation process',
        'self' => [
          'name' => 'instance'
        ],
        'type' => 'object',
        'properties' => [
          'schema' => ['type' => 'string'],
          'data'   => []
        ]
    ];
    
    /**
     * @var SchemaInstanceValidator
     */
    private $validator;

    /**
     * @var CacheItemPool
     */
    private $cachePool;

    /**
     * @var SchemaRepository
     */
    private $schemas;

    /**
     * @var mixed[]
     */
    private $instances;


    /**
     * SchemaInstanceRepository constructor.
     *
     * @param SchemaInstanceValidator $validator
     * @param SchemaRepository $schemas
     * @param CacheItemPool $cachePool
     */
    public function __construct(SchemaInstanceValidator $validator, SchemaRepository $schemas, CacheItemPool $cachePool = null)
    {
        $this->validator  = $validator;
        $this->schemas    = $schemas;
        $this->cachePool  = $cachePool;

        if (!$validator->hasKeyword('self'))
            $validator->addKeyword(new SelfKeyword());
    }


    /**
     * @param $schema
     * @return int
     */
    public function count($schema)
    {
        return count($this->instances[$schema]);
    }

    /**
     * @param DataProvider $dataProvider
     */
    public function addDataProvider(DataProvider $dataProvider)
    {
        $this->importFromDataProvider($dataProvider);
    }

    /**
     * @param SchemaProvider $schemaProvider
     */
    public function addSchemaProvider(SchemaProvider $schemaProvider)
    {
        $this->schemas->addSchemaProvider($schemaProvider);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->{$key};
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        list ($schema, $id) = explode(':', $key);

        if (isset($this->instances[$schema][$id]))
            return $this->{$key} = $this->schemas->getInstance($this->instances[$schema][$id]);

        return null;
    }

    /**
     * @param string $schema
     * @param array $where
     *
     * @return array
     */
    public function findBySchema($schema, $where = [])
    {
        $results = [];

        if (!isset($this->instances[$schema]))
            return $results;

        foreach (array_keys($this->instances[$schema]) as $id)
        {
            if (!$this->match($where, $instance = $this->{$schema.':'.$id}))
                continue;

            $results[] = $instance;
        }

        return $results;
    }

    /**
     * @param mixed $match
     * @param mixed $data
     *
     * @return bool
     */
    private function match($match, $data)
    {
        if (is_array($match))
        {
            foreach ($match as $field => $value)
            {
                if (!is_object($data))
                    return false;

                if (!isset($data->{$field}) || !$this->match($value, $data->{$field}))
                    return false;
            }

            return true;
        }
        else
        {
            return is_scalar($data) ? $match == $data : $match == (string) $data;
        }
    }

    /**
     * @param DataProvider $dataProvider
     */
    private function importFromDataProvider(DataProvider $dataProvider)
    {
        $data = [];

        foreach ($dataProvider as $instance)
        {
            // Pass 1: Validate Instance Schema

            $this->validator->validate(self::INSTANCE_SCHEMA, $instance);

            // Pass 2: Validate Self Describing Schema

            $this->validator->validate($this->schemas->getSchema($instance->schema), $instance->data);

            // Build Validated Array

            $data[] = $instance;
        }

        $this->importValidatedInstances($data);
    }

    private function importValidatedInstances($instances)
    {
        foreach ($instances as $instance)
            $this->instances[$instance->schema][$instance->data->id] = $instance;
    }
}