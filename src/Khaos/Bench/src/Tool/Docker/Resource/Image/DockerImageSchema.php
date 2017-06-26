<?php

namespace Khaos\Bench\Tool\Docker\Resource\Image;

use Khaos\Bench\Tool\Bench\Resource\Command\BenchCommandDefinition;
use Khaos\Bench\Tool\Docker\Resource\Image\DockerImageDefinition;
use Khaos\Bench\Resource\Schema\Schema;
use Khaos\Bench\Resource\Type\TypeRepository;

class DockerImageSchema implements Schema
{
    /**
     * Schema Name
     */
    const NAME = 'docker/image';

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
                    'name' => ['type' => 'expression']
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
        return new DockerImageDefinition($data, $this->typeRepository);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }
}