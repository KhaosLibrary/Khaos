<?php

namespace Khaos\FSM;

use Exception;

class StepRunner implements Runner
{
    /**
     * Context
     *
     * @var Stateful
     */
    private $context;

    /**
     * Step Runner
     *
     * A basic machine runner where each call to input correlates to a single
     * transition in the machine.
     *
     * @param State     $initialState
     * @param Stateful  $context
     */
    public function __construct(State $initialState, Stateful $context = null)
    {
        $this->context = $context ? : new Context();
        $this->context->setCurrentState($initialState);
    }

    /**
     * Get Context
     *
     * @return Stateful
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set Context
     *
     * @param Stateful $context
     *
     * @return void
     */
    public function setContext(Stateful $context)
    {
        $this->context = $context;
    }

    /**
     * Transitions available from current state
     *
     * @return Transition[]
     */
    public function getTransitions()
    {
        return $this->context->getCurrentState()->getTransitions();
    }

    /**
     * Advance
     *
     * Attempt to advance the machine with the given input, optionally try
     * and follow the exact path as given by the transition.
     *
     * @param mixed      $input
     * @param Transition $transition Take this path, otherwise try all paths
     *
     * @return mixed
     * @throws Exception
     */
    public function apply($input = null, Transition $transition = null)
    {
        $state = $this->context->getCurrentState();

        if ($transition === null && !$this->can($input, $transition)) {
            throw new StateException(sprintf(
                'No transition from %s accepted %s',
                (string)$state,
                $input
            ));
        }

        return $transition->apply($input, $this->context, $this);
    }

    /**
     * Can Advance
     *
     * Determine if the machine can be advanced, if so set transition
     * to the path we can take.
     *
     * Usage :-
     *
     * can(Input)
     * can(Input, Transition)
     *
     * @param mixed      $input        Input against which transition(s) will be tested
     * @param Transition $transition   Placeholder for found transition or the only transition to be tested
     *
     * @return bool
     * @throws Exception
     */
    public function can($input, &$transition = null)
    {
        $state = $this->context->getCurrentState();

        if (!($transition instanceof Transition)) {
            foreach ($state->getTransitions() as $candidate) {
                if (!$candidate->can($input, $this->context, $this)) {
                    continue;
                }

                $transition = $candidate;

                return true;
            }

            return false;
        }

        return $transition->can($input, $this->context, $this);
    }

    /**
     * Get Lambda
     *
     * @return callable
     */
    public function getLambda()
    {
        return [$this, 'apply'];
    }

    /**
     * Input
     *
     * @param $input
     *
     * @return mixed
     */
    public function input($input)
    {
        return $this->apply($input);
    }
}
