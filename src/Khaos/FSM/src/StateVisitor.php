<?php

namespace Khaos\FSM;

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
