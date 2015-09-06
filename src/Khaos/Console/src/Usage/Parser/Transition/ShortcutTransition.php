<?php

namespace Khaos\Console\Usage\Parser\Transition;

use Khaos\FSM\Runner\Runner;
use Khaos\FSM\State\State;
use Khaos\FSM\Stateful;
use Khaos\FSM\State\StateVisitor;
use Khaos\FSM\Transition\Transition;

class ShortcutTransition implements Transition
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
     */
    public function can($input, Stateful $context, Runner $runner)
    {
        return true;
    }

    /**
     * @inheritDoc
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
        return '*';
    }
}
