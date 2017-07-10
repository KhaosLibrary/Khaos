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
            'id'        => ['type' => 'string'],
            'namespace' => ['type' => 'string'],
            'command'   => ['type' => 'string'],
            'usage'     => ['type' => 'string']
        ]
    ];

    private $instanceFactory;

    public function __construct(Expression $expressionHandler)
    {
        $this->instanceFactory = new CommandFactory($expressionHandler);
    }

    public function getName()
    {
        return self::NAME;
    }

    public function getSchema()
    {
        return self::SCHEMA;
    }

    public function getInstanceFactory()
    {
        return $this->instanceFactory;
    }
}