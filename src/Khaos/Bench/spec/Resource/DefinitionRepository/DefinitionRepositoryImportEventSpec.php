<?php

namespace spec\Khaos\Bench\Resource\DefinitionRepository;

use Khaos\Bench\Resource\ResourceDefinition;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\Event;

class DefinitionRepositoryImportEventSpec extends ObjectBehavior
{
    function let(ResourceDefinition $resourceDefinition)
    {
        $this->beConstructedWith($resourceDefinition);
    }

    function it_is_an_event()
    {
        $this->shouldHaveType(Event::class);
    }

    function it_provides_the_resource_definition_which_triggered_the_event(ResourceDefinition $resourceDefinition)
    {
        $this->getResourceDefinition()->shouldBe($resourceDefinition);
    }
}
