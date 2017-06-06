<?php

namespace spec\Khaos\Bench\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\Definition\CommandNamespaceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use PhpSpec\ObjectBehavior;

class CommandNamespaceDefinitionSpec extends ObjectBehavior
{
    private $sample = [
        'resource' => 'bench/command-namespace',
        'metadata' => [
            'id'          => 'sample-command-namespace',
            'title'       => 'Some example title',
            'description' => 'Some example description.'
        ],
        'definition' => [
            'namespace' => 'docker'
        ]
    ];

    function let()
    {
        $this->beConstructedWith($this->sample);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandNamespaceDefinition::class);
    }

    function it_is_a_resource_definition()
    {
        $this->shouldHaveType(ResourceDefinition::class);
    }

    function it_provides_type_of_resource()
    {
        $this->getType()->shouldBe($this->sample['resource']);
    }

    function it_provides_the_namespace()
    {
        $this->getNamespace()->shouldBe($this->sample['definition']['namespace']);
    }

    function it_throws_an_exception_when_no_namespace_is_specified()
    {
        $sample = $this->sample;
        unset($sample['definition']['namespace']);

        $this->beConstructedWith($sample);
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
