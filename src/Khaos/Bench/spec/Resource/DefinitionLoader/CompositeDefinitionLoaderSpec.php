<?php

namespace spec\Khaos\Bench\Resource\DefinitionLoader;

use Khaos\Bench\Resource\DefinitionLoader\CompositeDefinitionLoader;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompositeDefinitionLoaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CompositeDefinitionLoader::class);
    }

    function it_is_a_resource_definition_loader()
    {
        $this->shouldHaveType(ResourceDefinitionLoader::class);
    }

    function it_provides_null_when_no_loader_is_able_to_load_the_given_resource_definition_source()
    {
        $this->load('non-existent')->shouldBe(null);
    }

    function it_provides_a_resource_definition_when_a_loader_is_able_to_handle_the_given_resource_definition_source(ResourceDefinitionLoader $resourceDefinitionLoader, ResourceDefinition $resourceDefinition)
    {
        $resourceDefinitionLoader->load('sample-resource.yml')->willReturn([$resourceDefinition]);

        $this->add($resourceDefinitionLoader);
        $this->load('sample-resource.yml')->shouldBe([$resourceDefinition]);
    }

    function it_can_make_use_of_multiple_definition_loaders(ResourceDefinitionLoader $resourceDefinitionLoader1, ResourceDefinitionLoader $resourceDefinitionLoader2, ResourceDefinition $resourceDefinition)
    {
        $resourceDefinitionLoader2->load('sample-resource.yml')->willReturn([$resourceDefinition]);

        $this->add($resourceDefinitionLoader1);
        $this->add($resourceDefinitionLoader2);

        $this->load('sample-resource.yml')->shouldBe([$resourceDefinition]);

        $resourceDefinitionLoader1->load('sample-resource.yml')->shouldHaveBeenCalled();
        $resourceDefinitionLoader2->load('sample-resource.yml')->shouldHaveBeenCalled();
    }
}
