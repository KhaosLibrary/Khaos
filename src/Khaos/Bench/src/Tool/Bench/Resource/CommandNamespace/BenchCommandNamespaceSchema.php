<?php

namespace Khaos\Bench\Tool\Bench\Resource\CommandNamespace;

use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Schema\Schema;
use Khaos\Bench\Resource\Type\TypeRepository;

class BenchCommandNamespaceSchema implements Schema
{
    const NAME = 'bench/command-namespace';

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
                    'namespace' => ['type' => 'string']
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
     * New definition from the given data applied to
     * this schema.
     *
     * @param array $data
     *
     * @return Definition
     */
    public function getDefinition(array $data)
    {
        return new BenchCommandNamespaceDefinition($data, $this->typeResolver);
    }

    /**
     * Name of schema
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}