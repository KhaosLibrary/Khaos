<?php

namespace Khaos\Bench\Tool\Bench\Resource\Command;

use Khaos\Bench\Tool\Bench\Resource\Command\BenchCommandDefinition;
use Khaos\Bench\Resource\Schema\Schema;
use Khaos\Bench\Resource\Type\TypeRepository;

class BenchCommandSchema implements Schema
{
    /**
     * Schema Name
     */
    const NAME = 'bench/command';

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
                'type' => 'map',
                'map'  => [
                    'namespace' => ['type' => 'string'],
                    'command'   => ['type' => 'string'],
                    'usage'     => ['type' => 'string'],
                    'options'   => [
                        'type'     => 'sequence',
                        'sequence' => [
                            'type' => 'string'
                        ]
                    ],
                    'tasks' => [
                        'type'     => 'sequence',
                        'sequence' => [
                            'type' => 'map',
                            'map'  => [
                                'title' => ['type' => 'string'],
                                'run'   => ['type' => 'expression']
                            ]
                        ]
                    ]
                ]
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
        return new BenchCommandDefinition($data, $this->typeResolver);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }
}