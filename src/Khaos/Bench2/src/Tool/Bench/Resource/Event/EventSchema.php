<?php

namespace Khaos\Bench2\Tool\Bench\Resource\Event;

use Khaos\Bench2\Expression;
use Khaos\Schema\Schema;

class EventSchema implements Schema
{
    const NAME = 'event';

    const SCHEMA = [
        'description' => '',
        'self' => [
            'name' => self::NAME
        ],
        'type' => 'object',
        'properties' => [
            'id'          => ['type' => 'string'],
            'title'       => ['type' => 'string'],
            'description' => ['type' => 'string']
        ]
    ];

    /**
     * @var Expression
     */
    private $expression;

    /**
     * EventSchema constructor.
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
        return new Event($this->expression, $data);
    }
}