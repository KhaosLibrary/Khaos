<?php

namespace spec\Khaos\FSM;

use Khaos\FSM\Runner;
use Khaos\FSM\State;
use Khaos\FSM\Stateful;
use Khaos\FSM\StepRunner;
use Khaos\FSM\Transition;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StepRunnerSpec extends ObjectBehavior
{
    /**
     * @param \Khaos\FSM\State $initialState
     * @param \Khaos\FSM\Stateful $context
     * @param \Khaos\FSM\Transition $t1
     * @param \Khaos\FSM\Transition $t2
     * @param \Khaos\FSM\State $s1
     * @param \Khaos\FSM\State $s2
     */
    function let($initialState, $context, $t1, $t2, $s1, $s2)
    {
        $context->setCurrentState($initialState)->willReturn();
        $context->getCurrentState()->willReturn($initialState);

        $this->beConstructedWith($initialState, $context);

        $t1->can('t1', $context, $this->getWrappedObject())->willReturn(true);
        $t1->can(Argument::any(), $context, $this->getWrappedObject())->willReturn(false);
        $t1->apply('t1', $context, $this->getWrappedObject())->willReturn('S1');
        $t1->getTo()->willReturn($s1);


        $t2->can('t2', $context, $this->getWrappedObject())->willReturn(true);
        $t2->can(Argument::any(), $context, $this->getWrappedObject())->willReturn(false);
        $t2->apply('t2', $context, $this->getWrappedObject())->willReturn('S2');
        $t2->getTo()->willReturn($s2);

        $initialState->getTransitions()->willReturn([$t1, $t2]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StepRunner::class);
    }

    function it_is_a_runner()
    {
        $this->shouldHaveType(Runner::class);
    }

    /**
     * @param \Khaos\FSM\Stateful $context
     */
    function it_holds_the_context_it_was_constructed_with($context)
    {
        $this->getContext()->shouldReturn($context);
    }

    /**
     * @param \Khaos\FSM\State $initialState
     * @param \Khaos\FSM\Transition $t1
     */
    function it_provides_the_transitions_of_the_current_state($initialState, $t1)
    {
        $initialState->getTransitions()->willReturn([$t1]);

        $this->getTransitions()->shouldReturn([$t1]);
    }

    /**
     * @param \Khaos\FSM\Transition $t1
     * @param \Khaos\FSM\Transition $t2
     */
    function it_can_test_if_the_given_input_is_valid_for_the_fsms_current_state($t1, $t2)
    {
        $this->can('t1')->shouldReturn($t1);
        $this->can('t2')->shouldReturn($t2);
        $this->can('t0')->shouldReturn(false);
    }

    /**
     * @param \Khaos\FSM\Transition $t1
     * @param \Khaos\FSM\Transition $t2
     */
    function it_only_tests_against_given_transition_if_specified($t1, $t2)
    {
        $this->can('t1', $t1)->shouldReturn($t1);
        $this->can('t2', $t1)->shouldReturn(false);
        $this->can('t0', $t1)->shouldReturn(false);
    }

    function it_applies_transition_when_given_valid_input()
    {
        $this->input('t1')->shouldReturn('S1');
    }
}
