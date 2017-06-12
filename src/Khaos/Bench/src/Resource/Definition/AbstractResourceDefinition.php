<?php

namespace Khaos\Bench\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\ResourceDefinition;

abstract class AbstractResourceDefinition implements ResourceDefinition
{
    protected $data = [];

    public function __construct(array $data)
    {
        $data['metadata']['title']             = $data['metadata']['title']             ?? null;
        $data['metadata']['description']       = $data['metadata']['description']       ?? null;
        $data['metadata']['working-directory'] = $data['metadata']['working-directory'] ?? null;

        if (!isset($data['metadata']['id']))
            throw new InvalidArgumentException('ID expected, none given.');

        $this->data = $data;
    }

    public function setMetaData($key, $value)
    {
        $this->data['metadata'][$key] = $value;
    }

    public function getId()
    {
        return $this->data['metadata']['id'];
    }

    public function getTitle()
    {
        return $this->data['metadata']['title'];
    }

    public function getDescription()
    {
        return $this->data['metadata']['description'];
    }

    public function getWorkingDirectory()
    {
        return $this->data['metadata']['working-directory'];
    }

    public abstract function getType();
}