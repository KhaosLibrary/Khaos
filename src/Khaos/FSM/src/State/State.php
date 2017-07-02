<?php

namespace Khaos\FSM\State;

use Khaos\FSM\State\StateTransitionBuilder;
use Khaos\FSM\State\StateVisitor;
use Khaos\FSM\State\StateVisitorClient;
use Khaos\FSM\Transition\Transition;

interface State extends StateVisitorClient
{
    const TYPE_INITIAL  = 'initial';
    const TYPE_NORMAL   = 'normal';
    const TYPE_TERMINAL = 'terminal';

    /**
     * Add Transition
     *
     * Usage :-
     *
     * addTransition(Transition)
     * addTransition(Label, To [,Guard [,Action]])
     *
     * @param string|Transition $transition
     * @param State             $to
     * @param callable          $guard
     * @param callable          $action
     *
     * @return void
     */
    public function addTransition($transition, State $to = null, $guard = null, $action = null);

    /**
     * Set ValidatorType
     *
     * @param string $type
     *
     * @return void
     */
    public function setType($type);

    /**
     * Is Initial
     *
     * @return bool
     */
    public function isInitial();

    /**
     * Is Normal
     *
     * @return bool
     */
    public function isNormal();

    /**
     * Is Terminal
     *
     * @return bool
     */
    public function isTerminal();

    /**
     * Get Transitions
     *
     * @return Transition[]
     */
    public function getTransitions();

    /**
     * Create Transition
     *
     * Fluid counterpart to addTransition
     *
     * @param string   $label
     * @param callable $guard
     *
     * @return StateTransitionBuilder
     */
    public function when($label, callable $guard = null);

    /**
     * Copy State
     *
     * Perform a deep clone
     *
     * @param State[] $visited
     *
     * @return State
     */
    public function copy(&$visited = []);

    /**
     * Accept Visitor
     *
     * @param StateVisitor    $visitor
     * @param State[]  $visited
     *
     * @return void
     */
    public function accept(StateVisitor $visitor, &$visited = []);

    /**
     * Get Label
     *
     * @return string
     */
    public function __toString();
}
