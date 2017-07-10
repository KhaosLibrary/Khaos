<?php

namespace Khaos\Bench2\Tool\Bench\Resource\CommandNamespace;

use Khaos\Schema\InstanceFactory;

class CommandNamespaceFactory implements InstanceFactory
{
    public function create($data)
    {
        return new CommandNamespace($data);
    }
}