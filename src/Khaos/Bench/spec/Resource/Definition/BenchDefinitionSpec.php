<?php

namespace spec\Khaos\Bench\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\Definition\BenchDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use PhpSpec\ObjectBehavior;

class BenchDefinitionSpec extends ObjectBehavior
{
    private $sample = [
        'metadata' => [
            'id'                => 'hello-world',
            'title'             => 'Hello World',
            'description'       => 'An example bench definition.',
            'working-directory' => 'sample/location'
        ],
        'tools' => [
            'docker',
            'kubernetes'
        ]
    ];

    function let()
    {
        $this->beConstructedWith($this->sample);
    }

    function it_is_a_resource()
    {
        $this->shouldHaveType(ResourceDefinition::class);
    }

    function it_provides_metadata_title()
    {
        $this->getTitle()->shouldBe($this->sample['metadata']['title']);
    }

    function it_provides_metadata_description()
    {
        $this->getDescription()->shouldBe($this->sample['metadata']['description']);
    }

    function it_provides_metadata_id()
    {
        $this->getId()->shouldBe($this->sample['metadata']['id']);
    }

    function it_provides_text_representation_of_resource_type()
    {
        $this->getType()->shouldBe(BenchDefinition::TYPE);
    }

    function it_throws_invalid_argument_exception_if_no_id_specified()
    {
        $this->beConstructedWith([
            'metadata' => [
                'title'       => 'Application Title',
                'description' => 'Application Description'
            ]
        ]);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_provides_metadata_working_directory()
    {
        $this->getWorkingDirectory()->shouldBe('sample/location');
    }

    function it_allows_metadata_to_be_set()
    {
        $this->getWorkingDirectory()->shouldBe('sample/location');
        $this->setMetadata('working-directory', 'different/location');
        $this->getWorkingDirectory()->shouldBe('different/location');
    }

    function it_provides_the_tools_that_will_be_required_for_the_bench()
    {
        $this->getTools()->shouldBe($this->sample['tools']);
    }

    function it_provides_an_empty_array_when_no_tools_are_specified_in_the_definition()
    {
        $this->beConstructedWith([
            'metadata' => [
                'id'          => 'hello-world',
                'title'       => 'Hello World',
                'description' => 'Example bench description...'
            ]
        ]);

        $this->getTools()->shouldBe([]);
    }

    function it_assigns_an_unique_id_when_none_specified()
    {
        $sample = $this->sample;
        unset($sample['metadata']['id']);

        $this->beConstructedWith($sample);

        $this->getId()->shouldBe('_internal/bench/0');

        $this::getUniqueId()->shouldBe('_internal/bench/1');
        $this::getUniqueId()->shouldBe('_internal/bench/2');
    }
}
