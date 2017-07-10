<?php

namespace Khaos\Bench2\Tool\Bench\Resource\Command;

use Khaos\Bench2\Expression;
use Khaos\Console\Usage\Input;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;

class Command
{
    private $data;

    private $expressionHandler;

    /**
     * Command constructor.
     *
     * @param Expression $expressionHandler
     * @param object $data
     */
    public function __construct(Expression $expressionHandler, $data)
    {
        $this->data = $data;
        $this->expressionHandler = $expressionHandler;
    }

    public function getNamespace()
    {
        return $this->data->namespace;
    }

    public function getUsage()
    {
        return $this->data->usage;
    }

    public function getOptions()
    {
        return new OptionDefinitionRepository();
    }

    public function run(Input $input)
    {
        echo $this->data->usage;
    }
}