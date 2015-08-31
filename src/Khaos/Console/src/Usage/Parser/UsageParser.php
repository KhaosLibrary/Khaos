<?php

namespace Khaos\Console\Usage\Parser;

use Khaos\Console\Usage\Input;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\FSM\BacktrackingRunner;
use Khaos\FSM\Definition;

class UsageParser
{
    /**
     * @var Definition
     */
    private $usageDefinition;

    /**
     * @var OptionDefinitionRepository
     */
    private $optionRepository;

    /**
     * @param Definition $usageDefinition
     * @param OptionDefinitionRepository $optionRepository
     */
    public function __construct(Definition $usageDefinition, OptionDefinitionRepository $optionRepository)
    {
        $this->usageDefinition  = $usageDefinition;
        $this->optionRepository = $optionRepository;
    }

    public function getDefinition()
    {
        return $this->usageDefinition;
    }

    public function getOptionDefinitions()
    {
        return $this->optionRepository;
    }

    /**
     * @param array $args
     *
     * @return Input|false
     */
    public function parse($args = null)
    {
        $fsm     = new BacktrackingRunner($this->usageDefinition->getInitialState(), new UsageParserContext());
        $symbols = $this->getInputSequence($args);

        if (!$symbols) {
            return false;
        }

        $result = $fsm->input($symbols);

        return $result === false ? false : new Input($result, $this->optionRepository);
    }

    private function getInputSequence($args = null)
    {
        if ($args instanceof InputSequence) {
            return $args;
        }

        return (new InputSequenceFactory())->createFrom($args, $this->optionRepository);
    }
}
