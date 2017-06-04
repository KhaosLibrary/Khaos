<?php

namespace spec\Khaos\Bench\Resource\DefinitionLoader;

use InvalidArgumentException;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use PhpSpec\ObjectBehavior;

class FileDefinitionLoaderSpec extends ObjectBehavior
{
    private $sample = __DIR__.'/../_sample/bench.yml';
    private $sampleRelativeWorkingDirectory = __DIR__.'/../_sample/relative-working-directory.yml';

    function it_is_a_resource_loader()
    {
        $this->shouldHaveType(ResourceDefinitionLoader::class);
    }

    function it_provides_null_if_the_file_does_not_exist()
    {
        $this->load('not-available.yml')->shouldBe(null);
    }

    function it_provides_null_if_none_of_the_specified_loaders_can_handle_the_file_type(ResourceDefinitionLoader $loader1, ResourceDefinitionLoader $loader2)
    {
        $this->add($loader1, ['json']);
        $this->add($loader2, ['xml']);

        $this->load($this->sample)->shouldBe(null);
    }

    function it_delegates_loading_of_resource_definitions_based_on_file_extension(
        ResourceDefinitionLoader $loader1,
        ResourceDefinitionLoader $loader2,
        ResourceDefinitionLoader $loader3,
        ResourceDefinition $resourceDefinition)
    {
        $this->add($loader1, ['json']);
        $this->add($loader2, ['xml']);
        $this->add($loader3, ['yml', 'yaml']);

        $loader3->load(file_get_contents($this->sample))->willReturn([$resourceDefinition]);

        $this->load($this->sample)->shouldBe([$resourceDefinition]);
    }

    function it_sets_the_working_directory_of_loaded_resources_to_the_location_of_the_file(ResourceDefinitionLoader $yamlDefinitionLoader, ResourceDefinition $resourceDefinition)
    {
        $yamlDefinitionLoader->load(file_get_contents($this->sample))->willReturn([$resourceDefinition]);

        $this->add($yamlDefinitionLoader, ['yml']);
        $this->load($this->sample);

        $resourceDefinition->setMetaData('working-directory', realpath(dirname($this->sample)))->shouldHaveBeenCalled();
    }

    function it_respects_relative_working_directories_specified_in_the_metadata(ResourceDefinitionLoader $yamlDefinitionLoader, ResourceDefinition $resourceDefinition)
    {
        $yamlDefinitionLoader->load(file_get_contents($this->sampleRelativeWorkingDirectory))->willReturn([$resourceDefinition]);

        $resourceDefinition->getWorkingDirectory()->willReturn('../');
        $resourceDefinition->setMetaData('working-directory', realpath(dirname($this->sampleRelativeWorkingDirectory).'/../'))->shouldBeCalled();

        $this->add($yamlDefinitionLoader, ['yml']);
        $this->load($this->sampleRelativeWorkingDirectory);
    }

    function it_throws_an_exception_when_an_absolute_working_directory_is_specified_in_the_metadata(ResourceDefinitionLoader $yamlDefinitionLoader, ResourceDefinition $resourceDefinition)
    {
        $yamlDefinitionLoader->load(file_get_contents($this->sample))->willReturn([$resourceDefinition]);

        $resourceDefinition->getWorkingDirectory()->willReturn('/');
        $this->add($yamlDefinitionLoader, ['yml']);

        $this->shouldThrow(InvalidArgumentException::class)->duringLoad($this->sample);
    }
}
