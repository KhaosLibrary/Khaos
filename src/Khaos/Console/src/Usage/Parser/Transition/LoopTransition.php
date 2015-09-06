<?php

namespace Khaos\Console\Usage\Parser\Transition;

use Khaos\Console\Usage\Parser\UsageParserContext;
use Khaos\FSM\Runner\BacktrackingRunner;
use Khaos\FSM\Runner\InputSequence;
use Khaos\FSM\Runner\Runner;
use Khaos\FSM\State\State;
use Khaos\FSM\Stateful;
use Khaos\FSM\State\StateVisitor;
use Khaos\FSM\Transition\Transition;

class LoopTransition implements Transition
{
    /**
     * @var State
     */
    private $to;

    /**
     * @param $to
     */
    public function __construct(State $to)
    {
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
     * @var InputSequence      $input
     * @var UsageParserContext $context
     * @var BacktrackingRunner $runner
     */
    public function can($input, Stateful $context, Runner $runner)
    {
        $last    = $context->getData($this);
        $current = $runner->getOutput();

        if ($last == $current) {
            return false;
        }

        $context->setData($this, $current);
        return true;
    }

    /**
     * @inheritDoc
     *
     * @var InputSequence      $input
     * @var UsageParserContext $context
     * @var BacktrackingRunner $runner
     */
    public function apply($input, Stateful $context, Runner $runner)
    {
        $context->setCurrentState($this->to);
        return null;
    }

    /**
     * @inheritDoc
     */
    public function copy(&$visited = [])
    {
        return new self($this->to->copy($visited));
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return 'loop';
    }
}
