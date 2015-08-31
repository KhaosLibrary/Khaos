<?php

namespace Khaos\FSM;

interface StateVisitorClient
{
    /**
     * Accept Visitor
     *
     * @param StateVisitor $visitor
     *
     * @return void
     */
    public function accept(StateVisitor $visitor);
}
