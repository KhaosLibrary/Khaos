<?php

namespace spec\Khaos\FSM;

use Khaos\FSM\State\DefaultState;
use Khaos\FSM\Transition\DefaultTransition;
use Khaos\FSM\State\State;
use Khaos\FSM\State\StateVisitor;
use Khaos\FSM\Transition\Transition;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefinitionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Default');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Khaos\FSM\Definition');
    }

    /**
     * @param \Khaos\FSM\State\State $state
     */
    function it_can_provide_states_by_state_label($state)
    {
        $state->__toString()->willReturn('S1');
        $this->addState($state);
        $this->getState('S1')->shouldReturn($state);
    }

    /**
     * @param \Khaos\FSM\State\State $state
     */
    function it_makes_the_first_added_state_the_initial_state($state)
    {
        $state->__toString()->willReturn('S1');
        $this->addState($state);
        $this->getInitialState()->shouldReturn($state);
    }

    function it_creates_default_state_if_no_state_by_label_exists()
    {
        $this->getState('S1')->shouldBeLike(new DefaultState('S1'));
    }

    /**
     * @param \Khaos\FSM\State\State       $s1
     * @param \Khaos\FSM\Transition\Transition  $t1
     */
    function it_can_add_transition_to_state($s1, $t1)
    {
        $s1->__toString()->willReturn('S1');
        $s1->addTransition(Argument::any())->willReturn(null);
        $this->addState($s1);
        $this->addTransition($t1, $s1);

        $s1->addTransition($t1)->shouldHaveBeenCalled();
    }

    /**
     * @param \Khaos\FSM\State\State $s1
     */
    function it_can_construct_default_transition_to_add_to_state($s1)
    {
        $s1->__toString()->willReturn('S1');
        $s2 = new DefaultState('S2');
        $s1->addTransition(Argument::any())->willReturn(null);
        $this->addState($s1);
        $this->addTransition('t1', $s1, $s2);

        $expect = new DefaultTransition('t1', $s2);

        $s1->addTransition(Argument::that(function($actual) use($expect) {
            return $actual == $expect;
        }))->shouldHaveBeenCalled();
    }

    /**
     * @param \Khaos\FSM\State\State         $s1
     * @param \Khaos\FSM\State\StateVisitor  $stateVisitor
     */
    function it_can_accept_state_visitor($s1, $stateVisitor)
    {
        $s1->__toString()->willReturn('S1');
        $s1->accept(Argument::any(), Argument::any())->willReturn(null);
        $this->addState($s1);

        $this->accept($stateVisitor);
        $visited = [];
        $s1->accept($stateVisitor, $visited)->shouldHaveBeenCalled();
    }

    /**
     * @param \Khaos\FSM\State\State $s1
     * @param \Khaos\FSM\State\State $s2
     */
    function it_can_have_the_initial_state_changed($s1, $s2)
    {
        $s1->__toString()->willReturn('S1');
        $s2->__toString()->willReturn('S2');

        $this->addState($s1);
        $this->addState($s2);

        $this->setInitialState($s2);
        $this->getInitialState()->shouldReturn($s2);
    }
}
