<?php

namespace Khaos\FSM\Runner;

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
}
