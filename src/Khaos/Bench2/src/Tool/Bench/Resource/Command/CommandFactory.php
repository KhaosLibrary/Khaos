<?php

namespace Khaos\Bench2\Tool\Bench\Resource\Command;

use Khaos\Bench2\Expression;
use Khaos\Schema\InstanceFactory;

class CommandFactory implements InstanceFactory
{
    private $expressionHandler;

    public function __construct(Expression $expressionHandler)
    {
        $this->expressionHandler = $expressionHandler;
    }

    public function create($data)
    {
        return new Command($this->expressionHandler, $data);
    }
}