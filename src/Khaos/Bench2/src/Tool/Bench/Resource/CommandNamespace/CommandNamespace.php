<?php

namespace Khaos\Bench2\Tool\Bench\Resource\CommandNamespace;

class CommandNamespace
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getNamespace()
    {
        return $this->data->namespace;
    }
}