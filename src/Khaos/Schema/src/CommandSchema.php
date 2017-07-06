<?php

namespace Khaos\Schema;

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
            'namespace' => ['type' => 'string'],
            'command'   => ['type' => 'string'],
            'usage'     => ['type' => 'string']
        ]
    ];

    public function getName()
    {
        return self::NAME;
    }

    public function getSchema()
    {
        return self::SCHEMA;
    }
}