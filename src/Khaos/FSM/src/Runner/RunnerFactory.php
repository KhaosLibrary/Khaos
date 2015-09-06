<?php

namespace Khaos\FSM\Runner;

use Khaos\FSM\Runner\Runner;
use Khaos\FSM\Runner\StepRunner;
use Khaos\FSM\State\State;
use Khaos\FSM\Stateful;

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
