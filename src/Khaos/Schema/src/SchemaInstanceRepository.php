<?php

namespace Khaos\Schema;

use Khaos\Cache\CacheItemPool;

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
     * @var SchemaCollection
     */
    private $schemas;

    /**
     * SchemaInstanceRepository constructor.
     *
     * @param SchemaInstanceValidator $validator
     * @param SchemaCollection $schemas
     * @param CacheItemPool|null $cachePool
     */
    public function __construct(SchemaInstanceValidator $validator, SchemaCollection $schemas, CacheItemPool $cachePool = null)
    {
        $this->validator = $validator;
        $this->cachePool = $cachePool;
        $this->schemas = $schemas;
    }

    /**
     * @param DataProvider $dataProvider
     */
    public function add(DataProvider $dataProvider)
    {
        $cacheItem = $this->cachePool->get($dataProvider->getName());

        if ($cacheItem->isHit())
        {
            $cachedInstanceData = $cacheItem->value();

            if ($cachedInstanceData['last_modified'] == $dataProvider->getLastModified())
            {
                $this->importValidatedInstances($cachedInstanceData['instances']);

                return;
            }
        }

        $this->importFromDataProvider($dataProvider, $cacheItem);
    }

    /**
     * @param $id
     */
    public function get($id)
    {

    }

    /**
     * @param $query
     */
    public function query($query)
    {

    }

    /**
     * @param $dataProvider
     * @param $cacheItem
     */
    private function importFromDataProvider($dataProvider, $cacheItem)
    {
        $data = [];

        foreach ($dataProvider as $instance)
        {
            // Pass 1: Validate Instance Schema

            $this->validator->validate(self::INSTANCE_SCHEMA, $instance);

            // Pass 2: Validate Self Describing Schema

            $this->validator->validate($this->schemas->get($instance->schema), $instance->data);

            // Build Validated Array

            $data[] = $instance;
        }

        $cacheItem->set([
            'last_modified' => $dataProvider->getLastModifer(),
            'instances'     => $data
        ]);

        $this->importValidatedInstances($data);
    }

    private function importValidatedInstances($instance)
    {

    }
}