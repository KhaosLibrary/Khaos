<?php

namespace spec\Khaos\Bench\Resource\DefinitionLoader;

use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use PhpSpec\ObjectBehavior;

class ArrayDefinitionLoaderSpec extends ObjectBehavior
{
    function let(ResourceDefinitionFactory $definitionFactory)
    {
        $this->beConstructedWith($definitionFactory);
    }

    function it_is_a_resource_definition_loader()
    {
        $this->shouldHaveType(ResourceDefinitionLoader::class);
    }

    function it_returns_null_for_any_source_which_is_not_an_array()
    {
        $this->load('not-valid')->shouldReturn(null);
    }

    function it_provides_a_resource_definition_of_the_type_specified_in_the_array(ResourceDefinition $resourceDefinition, ResourceDefinitionFactory $definitionFactory)
    {
        $sample = [
            'resource' => 'bench',
            'metadata' => [
                'title' => 'Test Bench Title',
                'description' => 'Test Bench Description'
            ]
        ];

        $definitionFactory->create($sample)->willReturn($resourceDefinition);

        $this->load($sample)->shouldBe([$resourceDefinition]);
    }

    function it_allows_multiple_resource_definitions_given_an_indexed_array(
        ResourceDefinitionFactory $definitionFactory,
        ResourceDefinition $resourceDefinition1,
        ResourceDefinition $resourceDefinition2
    )
    {
        $sample1 = [
            'resource' => 'bench-1',
            'metadata' => [
                'title' => 'Test Bench Title',
                'description' => 'Test Bench Description'
            ]
        ];

        $sample2 = [
            'resource' => 'bench-2',
            'metadata' => [
                'title' => 'Test Bench Title',
                'description' => 'Test Bench Description'
            ]
        ];

        $definitionFactory->create($sample1)->willReturn($resourceDefinition1);
        $definitionFactory->create($sample2)->willReturn($resourceDefinition2);

        $this->load([$sample1, $sample2])->shouldBe([$resourceDefinition1, $resourceDefinition2]);
    }
}
