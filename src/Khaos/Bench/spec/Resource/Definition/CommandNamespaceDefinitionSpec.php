<?php

namespace spec\Khaos\Bench\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Tool\Bench\Resource\Definition\NamespaceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
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
        $this->shouldHaveType(NamespaceDefinition::class);
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

    function it_assigns_an_unique_id_when_none_specified()
    {
        $sample = $this->sample;
        unset($sample['metadata']['id']);

        $this->beConstructedWith($sample);

        $this->getId()->shouldBe('_internal/bench/command-namespace/0');

        $this::getUniqueId()->shouldBe('_internal/bench/command-namespace/1');
        $this::getUniqueId()->shouldBe('_internal/bench/command-namespace/2');
    }
}
