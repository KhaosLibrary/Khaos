<?php

namespace Khaos\Bench2\Tool\Bench\Resource\CommandNamespace;

use Khaos\Schema\InstanceFactory;
use Khaos\Schema\Schema;

class CommandNamespaceSchema implements Schema
{
    const NAME = 'namespace';

    const SCHEMA = [
        'description' => '',
        'self' => [
            'name' => self::NAME
        ],
        'type' => 'object',
        'properties' => [
            'id'        => ['type' => 'string'],
            'namespace' => ['type' => 'string'],
        ]
    ];

    private $instanceFactory;

    public function __construct()
    {
        $this->instanceFactory = new CommandNamespaceFactory();
    }

    public function getName()
    {
        return self::NAME;
    }

    public function getSchema()
    {
        return self::SCHEMA;
    }

    /**
     * @return InstanceFactory
     */
    public function getInstanceFactory()
    {
        return $this->instanceFactory;
    }
}