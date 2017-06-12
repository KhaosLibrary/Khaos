<?php

namespace spec\Khaos\Bench\Command\Event;

use Khaos\Bench\Command\Event\InvalidUsageEvent;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\Console\Usage\Parser\InputSequence;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;

class InvalidUsageEventSpec extends ObjectBehavior
{
    function let(OptionDefinitionRepository $options)
    {
        $this->beConstructedWith([], $options);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InvalidUsageEvent::class);
    }

    function it_is_an_event()
    {
        $this->shouldHaveType(Event::class);
    }

    function it_provides_input_sequence_from_supplied_options_and_args()
    {
        $this->getInputSequence()->shouldBeAnInstanceOf(InputSequence::class);
    }
}
