<?php

namespace Khaos\Bench2\Tool\Bench\Resource\Command;

use Khaos\Bench2\Expression;
use Khaos\Bench2\Resource\GenericResource\GenericResource;
use Khaos\Bench2\Resource\Resource;
use Khaos\Console\Usage\Input;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;

class Command extends GenericResource implements Resource
{
    /**
     * Command constructor.
     *
     * @param Expression $expression
     * @param object $data
     */
    public function __construct(Expression $expression, $data)
    {
        parent::__construct($expression,CommandSchema::SCHEMA, $data);
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

    public function getNamespace()
    {
        return $this->namespace ?? 'bench';
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getUsage()
    {
        return $this->usage ?? $this->getNamespace().' '.$this->getCommand().' [options]';
    }

    public function getOptions()
    {
        $parser  = new OptionDefinitionParser();
        $options = new OptionDefinitionRepository();

        if (!isset($this->options))
            return $options;

        foreach ($this->options as $option)
            $options->add($parser->parse($option));

        return $options;
    }

    public function run(Input $input)
    {
        echo $this->action;
    }
}