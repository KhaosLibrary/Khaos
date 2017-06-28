<?php

namespace Khaos\Bench\Tool\Bench\Resource\SecretKey;

use Khaos\Bench\Resource\Schema\Schema;
use Khaos\Bench\Resource\Type\TypeRepository;

class SecretKeySchema implements Schema
{
    /**
     * Schema Name
     */
    const NAME = 'secret/key';

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
                        'key' => ['type' => 'expression']
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
        return new SecretKeyDefinition($data, $this->typeResolver);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }
}