<?php

namespace Khaos\Bench2\Tool\Bench\Resource\Command;

use Khaos\Bench2\Expression;
use Khaos\Schema\Schema;

class CommandSchema implements Schema
{
    const NAME = 'command';

    const SCHEMA = [
        'description' => '',
        'self' => [
            'name' => self::NAME
        ],
        'type' => 'object',
        'properties' => [
            'id' => [
                'type' => 'string'
            ],
            'title' => [
                'type' => 'string'
            ],
            'description' => [
                'type' => 'string'
            ],
            'namespace' => [
                'type' => 'string'
            ],
            'command' => [
                'type' => 'string'
            ],
            'usage' => [
                'type' => 'string'
            ],
            'options' => [
                'type'  => 'array',
                'items' => [
                    'type' => 'string'
                ]
            ],
            'action' => [
                'type'       => 'string',
                'expression' => true
            ]
        ]
    ];

    private $expression;

    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function getSchema()
    {
        return self::SCHEMA;
    }

    public function getInstance($data)
    {
        return new Command($this->expression, $data);
    }
}