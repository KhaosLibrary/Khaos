<?php

namespace Khaos\Console\Usage\Parser\Transition;

use Khaos\Console\Usage\Model\OptionDefinition;
use Khaos\Console\Usage\Parser\UsageParserContext;
use Khaos\Console\Usage\Parser\InputSequence;
use Khaos\FSM\Runner\BacktrackingRunner;
use Khaos\FSM\Runner\Runner;
use Khaos\FSM\State\State;
use Khaos\FSM\Stateful;
use Khaos\FSM\State\StateVisitor;
use Khaos\FSM\Transition\Transition;

class OptionTransition implements Transition
{
    /**
     * @var State
     */
    private $to;

    /**
     * @var OptionDefinition
     */
    private $optionDefinition;

    /**
     * Option Transition
     *
     * @param OptionDefinition $optionDefinition
     * @param State $to
     */
    public function __construct(OptionDefinition $optionDefinition, $to)
    {
        $this->to = $to;
        $this->optionDefinition = $optionDefinition;
    }

    /**
     * @inheritDoc
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @inheritDoc
     */
    public function setTo(State $state)
    {
        $this->to = $state;
    }

    /**
     * @inheritDoc
     */
    public function accept(StateVisitor $visitor, &$visited = [])
    {
        $this->to->accept($visitor, $visited);
    }

    /**
     * @inheritDoc
     *
     * @var InputSequence $input
     * @var UsageParserContext       $context
     * @var BacktrackingRunner       $runner
     */
    public function can($input, Stateful $context, Runner $runner)
    {
        return $input->hasOption($this->optionDefinition);
    }

    /**
     * @inheritDoc
     *
     * @var InputSequence $input
     * @var UsageParserContext       $context
     * @var BacktrackingRunner       $runner
     */
    public function apply($input, Stateful $context, Runner $runner)
    {
        $context->setCurrentState($this->to);

        return $input->getOption($this->optionDefinition);
    }

    /**
     * @inheritDoc
     */
    public function copy(&$visited = [])
    {
        return new self($this->optionDefinition, $this->to->copy($visited));
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $names = [];

        if (($longName = $this->optionDefinition->getLongName()) !== null) {
            $names[] = $longName;
        }

        if (($shortName = $this->optionDefinition->getShortName()) !== null) {
            $names[] = $shortName;
        }

        return 'option('.implode(' | ', $names).')::'.$this->optionDefinition->getType();
    }
}
