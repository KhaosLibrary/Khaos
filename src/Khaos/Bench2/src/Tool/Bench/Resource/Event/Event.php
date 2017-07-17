<?php

namespace Khaos\Bench2\Tool\Bench\Resource\Event;

use Khaos\Bench2\Expression;
use Khaos\Bench2\Resource\GenericResource\GenericResource;
use Khaos\Bench2\Resource\Resource;

class Event extends GenericResource implements Resource
{
    /**
     * Event constructor.
     *
     * @param Expression $expression
     * @param object $data
     */
    public function __construct(Expression $expression, $data)
    {
        parent::__construct($expression, EventSchema::SCHEMA, $data);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title ?? null;
    }

    public function getDescription()
    {
        return $this->description ?? null;
    }
}