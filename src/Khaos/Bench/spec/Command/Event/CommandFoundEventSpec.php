<?php

namespace spec\Khaos\Bench\Command\Event;

use Khaos\Bench\Command\Event\CommandFoundEvent;
use Khaos\Console\Usage\Input;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;

class CommandFoundEventSpec extends ObjectBehavior
{
    function let(Input $input)
    {
        $this->beConstructedWith($input);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandFoundEvent::class);
    }

    function it_is_an_event()
    {
        $this->shouldHaveType(Event::class);
    }

    function it_provides_the_parsed_input_of_the_command(Input $input)
    {
        $this->getInput()->shouldBe($input);
    }
}
