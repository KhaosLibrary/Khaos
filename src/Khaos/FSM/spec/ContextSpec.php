<?php

namespace spec\Khaos\FSM;

use Khaos\FSM\Context;
use Khaos\FSM\State\State;
use Khaos\FSM\Stateful;
use PhpSpec\ObjectBehavior;

class ContextSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Context::class);
    }

    function it_is_stateful()
    {
        $this->shouldHaveType(Stateful::class);
    }

    function it_holds_reference_to_set_state(State $state)
    {
        $this->setCurrentState($state);
        $this->getCurrentState()->shouldBe($state);
    }
}
