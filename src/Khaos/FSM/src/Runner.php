<?php

namespace Khaos\FSM;

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
