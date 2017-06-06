<?php

namespace spec\Khaos\Bench\Command;

use Khaos\Bench\Command\CommandRunnerInvalidUsageEvent;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\Console\Usage\Parser\InputSequence;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;

class CommandRunnerInvalidUsageEventSpec extends ObjectBehavior
{
    function let(OptionDefinitionRepository $options)
    {
        $this->beConstructedWith([], $options);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandRunnerInvalidUsageEvent::class);
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
