<?php

namespace spec\Khaos\Bench\Resource\DefinitionFactory;

use InvalidArgumentException;
use Khaos\Bench\Tool\Bench\Resource\Definition\BenchDefinition;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\BenchDefinitionFactory;
use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\ImportDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompositeDefinitionFactorySpec extends ObjectBehavior
{
    function let(BenchDefinitionFactory $benchDefinitionFactory, ImportDefinitionFactory $importDefinitionFactory)
    {
        $benchDefinitionFactory->getType()->willReturn(BenchDefinition::TYPE);
        $importDefinitionFactory->getType()->willReturn(ImportDefinition::TYPE);

        $this->add($benchDefinitionFactory);
        $this->add($importDefinitionFactory);
    }

    function it_is_a_resource_definition_factory()
    {
        $this->shouldHaveType(ResourceDefinitionFactory::class);
    }

    function it_uses_a_resource_factory_appropriate_to_the_resource_specified(BenchDefinitionFactory $benchDefinitionFactory, BenchDefinition $benchDefinition)
    {
        $benchDefinitionFactory->create(Argument::type('array'))->willReturn($benchDefinition);

        $this->create([
            'resource' => 'bench',
            'metadata' => [
                'id' => 'test',
                'title' => 'Test Title',
                'description' => 'Test Description'
            ]
        ]);

        $benchDefinitionFactory->create(Argument::type('array'))->shouldBeCalled();
    }

    function it_throws_invalid_argument_exception_when_no_application_resource_factory_can_be_found()
    {
        $this->shouldThrow(InvalidArgumentException::class)->duringCreate([
            'resource' => 'Unknown',
            'metadata' => [
                'id' => 'test',
                'title' => 'Test Title',
                'description' => 'Test Description'
            ]
        ]);
    }

    function it_provides_type_of_resource_factory()
    {
        $this->getType()->shouldBe('*');
    }
}
