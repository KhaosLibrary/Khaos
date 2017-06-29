<?php

namespace Khaos\Bench\Tool\Bench\Resource\Attributes;

use Khaos\Bench\Resource\Schema\Schema;
use Khaos\Bench\Resource\Type\TypeRepository;

class BenchAttributesSchema implements Schema
{
    /**
     * Schema Name
     */
    const NAME = 'bench/attributes';

    /**
     * Schema Definition
     */
    const SCHEMA =
    [
        'type' => 'map',
        'map'  => [
            'schema' => ['type' => 'string'],
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
                'type' => 'dynamic'
            ]
        ]
    ];

    /**
     * @var TypeRepository
     */
    private $typeResolver;

    /**
     * CommandSchema constructor.
     *
     * @param TypeRepository $typeResolver
     */
    public function __construct(TypeRepository $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    /**
     * @inheritdoc
     */
    public function getDefinition(array $data)
    {
        return new BenchAttributesDefinition($data, $this->typeResolver);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }
}