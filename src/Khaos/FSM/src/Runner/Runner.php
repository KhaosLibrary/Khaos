<?php

namespace Khaos\FSM\Runner;

use Khaos\FSM\State\State;

interface Runner
{
    /**
     * Input
     *
     * @param $input
     *
     * @return mixed
     */
    public function input($input);

    /**
     * @return callable
     */
    public function getLambda();

    /**
     * Alias of input
     *
     * @param $input
     *
     * @return mixed
     */
    public function __invoke($input);

    /**
     * Get Current State
     *
     * @return State
     */
    public function getCurrentState();
}
