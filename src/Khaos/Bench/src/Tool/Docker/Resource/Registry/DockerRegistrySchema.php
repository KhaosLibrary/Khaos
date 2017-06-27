<?php

namespace Khaos\Bench\Tool\Docker\Resource\Registry;

use Khaos\Bench\Resource\Schema\Schema;
use Khaos\Bench\Resource\Type\TypeRepository;

/**
 * Class DockerRegistrySchema
 *
 * @package Khaos\Bench\Tool\Docker\Resource\Image
 */
class DockerRegistrySchema implements Schema
{
    /**
     * Schema Name
     */
    const NAME = 'docker/registry';

    /**
     * Schema Definition
     */
    const SCHEMA =
    [
        'type' => 'map',
        'map'  => [
            'schema' => [
                'type'  => 'string'
            ],
            'metadata' => [
                'type' => 'map',
                'map'  => [
                    'id'                => ['type' => 'string'],
                    'title'             => ['type' => 'string'],
                    'description'       => ['type' => 'string'],
                    'working-directory' => ['type' => 'string'],
                    'source-file'       => ['type' => 'string']
                ]
            ],
            'definition' => [
                'type' => 'map',
                'map'  => [
                    'server'   => ['type' => 'string'],
                    'username' => ['type' => 'string'],
                    'password' => ['type' => 'expression']
                ]
            ]
        ]
    ];

    /**
     * @var TypeRepository
     */
    private $typeRepository;

    /**
     * CommandSchema constructor.
     *
     * @param TypeRepository $typeRepository
     */
    public function __construct(TypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    /**
     * @inheritdoc
     */
    public function getDefinition(array $data)
    {
        return new DockerRegistryDefinition($data, $this->typeRepository);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }
}