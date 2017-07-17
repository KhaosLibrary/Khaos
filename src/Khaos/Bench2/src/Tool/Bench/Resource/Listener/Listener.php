<?php

namespace Khaos\Bench2\Tool\Bench\Resource\Listener;

use Khaos\Bench2\Expression;
use Khaos\Bench2\Resource\GenericResource\GenericResource;
use Khaos\Bench2\Resource\Resource;

class Listener extends GenericResource implements Resource
{
    public function __construct(Expression $expression, $data)
    {
        parent::__construct($expression, ListenerSchema::SCHEMA, $data);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function __invoke()
    {
        return $this->action;
    }
}