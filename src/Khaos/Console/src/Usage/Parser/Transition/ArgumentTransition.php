<?php

namespace Khaos\Console\Usage\Parser\Transition;

use Khaos\Console\Usage\Model\Argument;
use Khaos\Console\Usage\Parser\UsageParserContext;
use Khaos\Console\Usage\Parser\InputSequence;
use Khaos\FSM\Runner\BacktrackingRunner;
use Khaos\FSM\Runner\Runner;
use Khaos\FSM\State\State;
use Khaos\FSM\Stateful;
use Khaos\FSM\State\StateVisitor;
use Khaos\FSM\Transition\Transition;

class ArgumentTransition implements Transition
{
    /**
     * @var string
     */
    private $argument;

    /**
     * @var State
     */
    private $to;

    /**
     * @param $argument
     * @param $to
     */
    public function __construct($argument, $to)
    {
        $this->argument = $argument;
        $this->to = $to;
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
        return $input->hasPositional();
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

        return new Argument($this->argument, $input->pop());
    }

    /**
     * @inheritDoc
     */
    public function copy(&$visited = [])
    {
        return new self($this->argument, $this->to->copy($visited));
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return '<'.$this->argument.'>';
    }
}
