<?php

namespace Khaos\Bench2\Tool\Bench\Resource\CommandNamespace;

use Khaos\Bench2\Expression;
use Khaos\Bench2\Resource\GenericResource\GenericResource;
use Khaos\Bench2\Resource\Resource;

class CommandNamespace extends GenericResource implements Resource
{
    /**
     * CommandNamespace constructor.
     *
     * @param Expression $expression
     * @param object $data
     */
    public function __construct(Expression $expression, $data)
    {
        parent::__construct($expression,CommandNamespaceSchema::SCHEMA, $data);
    }

    public function getTitle()
    {
        return $this->title ?? null;
    }

    public function getDescription()
    {
        return $this->description ?? null;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
}