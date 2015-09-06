<?php

namespace spec\Khaos\FSM;

use Exception;
use Khaos\FSM\DefaultState;
use Khaos\FSM\DefaultTransition;
use Khaos\FSM\Definition;
use Khaos\FSM\DefinitionTransitionBuilder;
use Khaos\FSM\State;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefinitionTransitionBuilderSpec extends ObjectBehavior
{
    /**
     * @param \Khaos\FSM\Definition $definition
     */
    function let($definition)
    {
        $this->beConstructedWith($definition);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefinitionTransitionBuilder::class);
    }

    function it_throws_exception_when_from_state_is_missing()
    {
        $this->shouldThrow(new Exception('Missing From State'))->duringDone();
    }

    /**
     * @param \Khaos\FSM\State $from
     */
    function it_throws_exception_when_target_state_is_missing($from)
    {
        $this->from($from);
        $this->shouldThrow(new Exception('Missing To State'))->duringDone();
    }

    /**
     * @param \Khaos\FSM\State $from
     */
    function it_builds_default_transition_based_on_given_details($from)
    {
        $to     = new DefaultState('S2');
        $guard  = function() { return true; };
        $action = function() { return 'applied'; };
        $expect = new DefaultTransition('t1', $to, $guard, $action);

        $this
            ->from($from)
            ->to($to)
            ->when('t1', $guard)
            ->then($action);

        $this->done();

        $from->addTransition($expect)->shouldHaveBeenCalled();
    }

    /**
     * @param \Khaos\FSM\Definition  $definition
     * @param \Khaos\FSM\State       $from
     * @param \Khaos\FSM\State       $to
     */
    function it_returns_definition_after_successful_call_to_done($definition, $from, $to)
    {
        $this
            ->from($from)
            ->to($to)
            ->when('t1')
            ->then('applied');

        $this->done()->shouldReturn($definition);
    }

    /**
     * @param \Khaos\FSM\Definition  $definition
     * @param \Khaos\FSM\State       $from
     * @param \Khaos\FSM\State       $to
     */
    function it_allows_the_then_step_to_be_skipped($definition, $from, $to)
    {
        $this
            ->from($from)
            ->to($to)
            ->when('t1');

        $this->done()->shouldReturn($definition);
    }
}
