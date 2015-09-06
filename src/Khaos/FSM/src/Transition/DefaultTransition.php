<?php

namespace Khaos\FSM\Transition;

use Khaos\FSM\Runner\InputSequence;
use Khaos\FSM\Runner\Runner;
use Khaos\FSM\State\State;
use Khaos\FSM\State\StateVisitor;
use Khaos\FSM\Stateful;
use Khaos\FSM\Transition\Transition;

class DefaultTransition implements Transition
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var State
     */
    private $to;

    /**
     * @var callable
     */
    private $guard;

    /**
     * @var callable
     */
    private $action;

    /**
     * Transition
     *
     * @param string   $label
     * @param State    $to
     * @param callable $guard
     * @param callable $action
     */
    public function __construct($label, $to, $guard = null, $action = null)
    {
        $this->label  = $label;
        $this->to     = $to;
        $this->guard  = $guard;
        $this->action = $action;
    }

    /**
     * Get To
     *
     * @return State
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set To
     *
     * @param State $state
     *
     * @return void
     */
    public function setTo(State $state)
    {
        $this->to = $state;
    }

    /**
     * Accept Visitor
     *
     * @param StateVisitor  $visitor
     * @param State[]       $visited
     *
     * @return void
     */
    public function accept(StateVisitor $visitor, &$visited = [])
    {
        $this->to->accept($visitor, $visited);
    }

    /**
     * Can Accept Input
     *
     * @param mixed    $input
     * @param Stateful $context
     * @param Runner   $runner
     *
     * @return bool
     */
    public function can($input, Stateful $context, Runner $runner)
    {
        if (!is_null($this->guard)) {
            return call_user_func($this->guard, $input, $context, $runner, $this);
        }

        if ($input instanceof InputSequence) {
            if ($input->size() == 0) {
                return false;
            }

            if ($input->peek() == $this->label) {
                return true;
            }
        }

        return $this->label == $input;
    }

    /**
     * Apply Transition
     *
     * @param mixed    $input
     * @param Stateful $context
     * @param Runner   $runner
     *
     * @return mixed
     */
    public function apply($input, Stateful $context, Runner $runner)
    {
        $context->setCurrentState($this->to);

        if ($this->action !== null && is_callable($this->action)) {
            return call_user_func($this->action, $input, $context, $runner, $this);
        }

        if ($input instanceof InputSequence) {
            $input->pop();
        }

        return $this->action?:(string)$this->to;
    }

    /**
     * Copy
     *
     * Perform deep clone
     *
     * @param State[] $visited
     *
     * @return Transition
     */
    public function copy(&$visited = [])
    {
        return new DefaultTransition($this->label, $this->to->copy($visited), $this->guard, $this->action);
    }

    /**
     * Get Label
     *
     * @return string
     */
    public function __toString()
    {
        return $this->label;
    }
}
