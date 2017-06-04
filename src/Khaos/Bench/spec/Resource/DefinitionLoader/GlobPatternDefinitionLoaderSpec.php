<?php

namespace spec\Khaos\Bench\Resource\DefinitionLoader;

use Khaos\Bench\Resource\DefinitionLoader\FileDefinitionLoader;
use Khaos\Bench\Resource\DefinitionLoader\GlobPatternDefinitionLoader;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GlobPatternDefinitionLoaderSpec extends ObjectBehavior
{
    function let(FileDefinitionLoader $fileDefinitionLoader)
    {
        $this->beConstructedWith($fileDefinitionLoader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GlobPatternDefinitionLoader::class);
    }

    function it_is_a_resource_definition_loader()
    {
        $this->shouldHaveType(ResourceDefinitionLoader::class);
    }

    function it_provides_all_resource_definitions_based_on_the_given_glob_pattern()
    {
        $this->load('docker/*/manifest.yml')->shouldBe([]);
    }
}
