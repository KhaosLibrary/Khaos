<?php

namespace Khaos\FSM;

class RunnerFactory
{
    private $defaultRunner = StepRunner::class;

    /**
     * Build FSM
     *
     * @param State    $initialState
     * @param Stateful $context
     *
     * @return Runner
     */
    public function buildFSM(State $initialState, Stateful $context = null)
    {
        return new $this->defaultRunner($initialState, $context);
    }
}
