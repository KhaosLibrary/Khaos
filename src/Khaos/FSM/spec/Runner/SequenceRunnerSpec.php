<?php

namespace spec\Khaos\FSM\Runner;

use Khaos\FSM\Runner\SequenceRunner;
use Khaos\FSM\State\State;
use Khaos\FSM\Stateful;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SequenceRunnerSpec extends ObjectBehavior
{
    function let(State $initialState, Stateful $context)
    {
        $this->beConstructedWith($initialState, $context);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SequenceRunner::class);
    }

    function it_holds_the_context_it_was_constructed_with(Stateful $context)
    {
        $this->getContext()->shouldReturn($context);
    }
}
