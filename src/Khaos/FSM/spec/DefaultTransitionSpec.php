<?php

namespace spec\Khaos\FSM;

use Khaos\FSM\DefaultState;
use Khaos\FSM\DefaultTransition;
use Khaos\FSM\Runner;
use Khaos\FSM\State;
use Khaos\FSM\Stateful;
use Khaos\FSM\StateVisitor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultTransitionSpec extends ObjectBehavior
{
    /**
     * @param \Khaos\FSM\State $to
     */
    function let($to)
    {
        $this->beConstructedWith('Transition A', $to);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefaultTransition::class);
    }

    /**
     * @param \Khaos\FSM\State $to
     */
    function it_provides_state_to_be_transitioned_to($to)
    {
        $this->getTo()->shouldBe($to);
    }

    /**
     * @param \Khaos\FSM\State $newTo
     */
    function it_allows_the_transition_to_state_to_be_changed($newTo)
    {
        $this->setTo($newTo);
        $this->getTo()->shouldBe($newTo);
    }

    /**
     * @param \Khaos\FSM\StateVisitor $visitor
     * @param \Khaos\FSM\State        $to
     */
    function it_can_accept_a_state_visitor($visitor, $to)
    {
        $visited = [];

        $this->accept($visitor);
        $to->accept($visitor, $visited)->shouldHaveBeenCalled();
    }

    /**
     * @param \Khaos\FSM\State    $to
     * @param \Khaos\FSM\Stateful $context
     * @param \Khaos\FSM\Runner   $runner
     */
    function it_uses_guard_when_specified_to_check_if_transitioning_is_possible($to, $context, $runner)
    {
        $guard = function($input) { return $input == 'my_token'; };

        $this->beConstructedWith('Transition A', $to, $guard);

        $this->can('my_token', $context, $runner)->shouldBe(true);
        $this->can('not_my_token', $context, $runner)->shouldBe(false);
    }

    /**
     * @param \Khaos\FSM\State    $to
     * @param \Khaos\FSM\Stateful $context
     * @param \Khaos\FSM\Runner   $runner
     */
    function it_compares_input_to_label_when_no_guard_is_specified($to, $context, $runner)
    {
        $this->beConstructedWith('switch_on', $to);

        $this->can('switch_on', $context, $runner)->shouldBe(true);
        $this->can('switch_off', $context, $runner)->shouldBe(false);
    }

    /**
     * @param \Khaos\FSM\State    $to
     * @param \Khaos\FSM\Stateful $context
     * @param \Khaos\FSM\Runner   $runner
     */
    function it_can_transition_the_specified_context_to_the_set_state($to, $context, $runner)
    {
        $to->__toString()->willReturn('To');
        $this->apply('Transition A', $context, $runner);
        $context->setCurrentState($to)->shouldHaveBeenCalled();
    }

    /**
     * @param \Khaos\FSM\State $to
     */
    function it_can_be_copied($to)
    {
        $visited = [];
        $to->copy($visited)->willReturn(new DefaultState('State A'));

        $this->copy()->shouldBeLike(new DefaultTransition('Transition A', new DefaultState('State A'), null, null));
    }

    function it_provides_label_when_toString_is_called()
    {
        $this->__toString()->shouldBe('Transition A');
    }
}
