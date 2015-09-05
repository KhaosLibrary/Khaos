<?php

namespace spec\Khaos\FSM;

use Khaos\FSM\SequenceRunner;
use Khaos\FSM\State;
use Khaos\FSM\Stateful;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SequenceRunnerSpec extends ObjectBehavior
{
    /**
     * @param \Khaos\FSM\State       $initialState
     * @param \Khaos\FSM\Stateful    $context
     */
    function let($initialState, $context)
    {
        $this->beConstructedWith($initialState, $context);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SequenceRunner::class);
    }

    /**
     * @param \Khaos\FSM\Stateful $context
     */
    function it_holds_the_context_it_was_constructed_with($context)
    {
        $this->getContext()->shouldReturn($context);
    }
}
