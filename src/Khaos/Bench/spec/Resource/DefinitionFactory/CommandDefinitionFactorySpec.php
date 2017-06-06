<?php

namespace spec\Khaos\Bench\Resource\DefinitionFactory;

use Khaos\Bench\Resource\Definition\CommandDefinition;
use Khaos\Bench\Resource\DefinitionFactory\CommandDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use PhpSpec\ObjectBehavior;

class CommandDefinitionFactorySpec extends ObjectBehavior
{
    private $sample = [
        'resource' => 'bench/command',
        'metadata' => [
            'id' => 'sample-command',
            'title' => 'Some example title',
            'description' => 'Some example command description'
        ],
        'definition' => [
            'namespace' => 'docker',
            'command'   => 'build <image=application>',
            'options'   => [
                '-e, --environment=<environment> Environment [default: development] the image should be built to target.'
            ]
        ]
    ];

    function let(OptionDefinitionParser $optionDefinitionParser)
    {
        $this->beConstructedWith($optionDefinitionParser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandDefinitionFactory::class);
    }

    function it_is_a_resource_definition_factory()
    {
        $this->shouldHaveType(ResourceDefinitionFactory::class);
    }

    function it_provides_type_of_resource_factory()
    {
        $this->getType()->shouldBe(CommandDefinition::TYPE);
    }

    function it_provides_command_resource_definition_when_given_data()
    {
        $this->create($this->sample)->shouldBeAnInstanceOf(CommandDefinition::class);
    }
}
