<?php

namespace spec\Khaos\Bench\Resource\DefinitionFactory;

use Khaos\Bench\Resource\Definition\CommandNamespaceDefinition;
use Khaos\Bench\Resource\DefinitionFactory\CommandNamespaceDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use PhpSpec\ObjectBehavior;

class CommandNamespaceDefinitionFactorySpec extends ObjectBehavior
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

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandNamespaceDefinitionFactory::class);
    }

    function it_is_a_resource_definition_fcatory()
    {
        $this->shouldHaveType(ResourceDefinitionFactory::class);
    }

    function it_provides_the_type_of_resource_factory()
    {
        $this->getType()->shouldBe(CommandNamespaceDefinition::TYPE);
    }

    function it_provides_the_command_namespace_definition_wheb_given_valid_data()
    {
        $this->create($this->sample)->shouldBeAnInstanceOf(CommandNamespaceDefinition::class);
    }
}
