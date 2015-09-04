<?php

namespace spec\Khaos\FSM;

use Exception;
use Khaos\FSM\DefaultState;
use Khaos\FSM\DefaultTransition;
use Khaos\FSM\State;
use Khaos\FSM\StateTransitionBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StateTransitionBuilderSpec extends ObjectBehavior
{
    /**
     * @param \Khaos\FSM\State $from
     */
    function let($from)
    {
        $this->beConstructedWith($from);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StateTransitionBuilder::class);
    }

    function it_throws_exception_when_target_state_is_missing()
    {
        $this->shouldThrow(new Exception('Missing To State'))->duringDone();
    }

    /**
     * @param \Khaos\FSM\State $to
     */
    function it_throws_exception_when_label_is_missing($to)
    {
        $this->to($to);
        $this->shouldThrow(new Exception('Missing Label'))->duringDone();
    }

    /**
     * @param \Khaos\FSM\State $from
     */
    function it_builds_default_transition_based_on_given_details($from)
    {
        $to     = new DefaultState('S1');
        $guard  = function() { return true; };
        $action = function() { return 'applied'; };
        $expect = new DefaultTransition('t1', $to, $guard, $action);

        $this
            ->to($to)
            ->when('t1', $guard)
            ->then($action);

        $this->done();

        $from->addTransition($expect)->shouldHaveBeenCalled();
    }

    /**
     * @param \Khaos\FSM\State $from
     * @param \Khaos\FSM\State $to
     */
    function it_provides_from_state_after_successfully_building_transition($from, $to)
    {
        $this
            ->to($to)
            ->when('t1')
            ->then('applied');

        $this->done()->shouldReturn($from);
    }

    /**
     * @param \Khaos\FSM\State $from
     * @param \Khaos\FSM\State $to
     */
    function it_allows_the_then_step_to_be_skipped($from, $to)
    {
        $this
            ->to($to)
            ->when('t1');

        $this->done()->shouldReturn($from);
    }
}
