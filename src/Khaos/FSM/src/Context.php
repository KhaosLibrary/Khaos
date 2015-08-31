<?php

namespace Khaos\FSM;

class Context implements Stateful
{
    private $currentState = null;

    /**
     * Get Current State
     *
     * @return State
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }

    /**
     * Set Current State
     *
     * @param State $state
     *
     * @return void
     */
    public function setCurrentState(State $state)
    {
        $this->currentState = $state;
    }
}
