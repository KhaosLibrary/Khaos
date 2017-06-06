<?php

namespace spec\Khaos\Bench\Resource\Definition;

use Exception;
use InvalidArgumentException;
use Khaos\Bench\Resource\Definition\CommandDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Console\Usage\Model\OptionDefinition;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use PhpSpec\ObjectBehavior;

class CommandDefinitionSpec extends ObjectBehavior
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
        $this->beConstructedWith($this->sample, $optionDefinitionParser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandDefinition::class);
    }

    function it_is_a_resource_definition()
    {
        $this->shouldHaveType(ResourceDefinition::class);
    }

    function it_provides_the_command_namespace()
    {
        $this->getNamespace()->shouldBe($this->sample['definition']['namespace']);
    }

    function it_provides_null_when_no_namespace_is_specified(OptionDefinitionParser $optionDefinitionParser)
    {
        $sample = $this->sample;
        unset($sample['definition']['namespace']);

        $this->beConstructedWith($sample, $optionDefinitionParser);
        $this->getNamespace()->shouldBe(null);
    }

    function it_provides_the_command()
    {
        $this->getCommand()->shouldBe($this->sample['definition']['command']);
    }

    function it_throws_an_exception_if_no_command_is_specified_in_the_definition(OptionDefinitionParser $optionDefinitionParser)
    {
        $sample = $this->sample;
        unset($sample['definition']['command']);

        $this->beConstructedWith($sample, $optionDefinitionParser);
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_provides_the_command_options(OptionDefinitionParser $optionDefinitionParser, OptionDefinition $optionDefinition)
    {
        $optionDefinition->getLabel()->willReturn('label');
        $optionDefinition->getShortName()->willReturn('short-name');
        $optionDefinition->getLongName()->willReturn('long-name');

        $optionDefinitionParser->parse('-e, --environment=<environment> Environment [default: development] the image should be built to target.')->willReturn($optionDefinition);

        /** @var CommandDefinition $commandDefinition */
        $commandDefinition = $this->getWrappedObject();
        $optionRepository  = $commandDefinition->getOptions();

        if (!$optionRepository->find('short-name'))
            throw new Exception('Unable to get the options from the command.');
    }

}
