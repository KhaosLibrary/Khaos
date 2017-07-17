<?php

namespace Khaos\Bench2\Tool\Bench\Resource\Listener;

use Khaos\Bench2\Expression;
use Khaos\Schema\Schema;

class ListenerSchema implements Schema
{
    const NAME = 'listener';

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
            'event'       => ['type' => 'string'],
            'action'      => ['type' => 'string', 'expression' => true]
        ]
    ];

    /**
     * @var Expression
     */
    private $expression;

    /**
     * ListenerSchema constructor.
     *
     * @param Expression $expression
     */
    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return array
     */
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
        return new Listener($this->expression, $data);
    }
}