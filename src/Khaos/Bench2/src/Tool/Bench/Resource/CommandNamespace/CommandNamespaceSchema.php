<?php

namespace Khaos\Bench2\Tool\Bench\Resource\CommandNamespace;

use Khaos\Bench2\Expression;
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
            'id'          => ['type' => 'string'],
            'title'       => ['type' => 'string'],
            'description' => ['type' => 'string'],
            'namespace'   => ['type' => 'string'],
        ]
    ];

    /**
     * @var Expression
     */
    private $expression;

    /**
     * CommandNamespaceSchema constructor.
     *
     * @param Expression $expression
     */
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

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function getInstance($data)
    {
        return new CommandNamespace($this->expression, $data);
    }
}