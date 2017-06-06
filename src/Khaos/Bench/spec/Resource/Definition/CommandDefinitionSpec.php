<?php

namespace spec\Khaos\Bench\Resource\Definition;

use Exception;
use InvalidArgumentException;
use Khaos\Bench\Resource\Definition\CommandDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Console\Usage\Model\Option;
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
            'command'   => 'build',
            'usage'     => 'bench docker build <image>',
            'options'   => [
                '-e, --environment=<environment> Environment [default: development] the image should be built to target.'
            ],
            'run' => [
                'Hello World'
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

    function it_ensures_the_command_is_a_single_word(OptionDefinitionParser $optionDefinitionParser)
    {
        $sample = $this->sample;
        $sample['definition']['command'] = 'hello world!';

        $this->beConstructedWith($sample, $optionDefinitionParser);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_generates_a_default_usage_pattern_when_none_is_specified(OptionDefinitionParser $optionDefinitionParser)
    {
        $sample = $this->sample;
        unset($sample['definition']['usage']);

        $this->beConstructedWith($sample, $optionDefinitionParser);

        $this->getUsage()->shouldBe('bench docker build [options]');
    }

    function it_ensures_the_options_catch_all_is_part_of_the_usage_pattern_given_usage_pattern_is_defined()
    {
        $this->getUsage()->shouldBe($this->sample['definition']['usage'].' [options]');
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

    function it_assigns_an_unique_id_when_none_specified(OptionDefinitionParser $optionDefinitionParser)
    {
        $sample = $this->sample;
        unset($sample['metadata']['id']);

        $this->beConstructedWith($sample, $optionDefinitionParser);

        $this->getId()->shouldBe('_internal/bench/command/0');

        $this::getUniqueId()->shouldBe('_internal/bench/command/1');
        $this::getUniqueId()->shouldBe('_internal/bench/command/2');
    }

    function it_provides_the_tasks_to_perform()
    {
        $this->getRun()->shouldBe($this->sample['definition']['run']);
    }
}
