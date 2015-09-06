<?php

namespace Khaos\FSM\Utility;

use Khaos\FSM\State\State;
use Khaos\FSM\State\StateVisitor;
use Khaos\FSM\State\StateVisitorClient;
use SplObjectStorage;

class GraphBuilder implements StateVisitor
{
    /**
     * @var SplObjectStorage
     */
    public $transitions;

    private $id = 0;

    public function __construct(StateVisitorClient $state)
    {
        $this->transitions = new SplObjectStorage();
        $state->accept($this);
    }

    /**
     * Visit State
     *
     * @param State $state
     *
     * @return void
     */
    public function visit(State $state)
    {
        foreach ($state->getTransitions() as $transition) {
            $this->transitions[$transition] = 'T'.(++$this->id);
        }
    }
}
