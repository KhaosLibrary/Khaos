<?php

namespace Khaos\FSM\State;

use Khaos\FSM\State\State;

interface StateVisitor
{
    /**
     * Visit State
     *
     * @param State $state
     *
     * @return void
     */
    public function visit(State $state);
}
