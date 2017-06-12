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

    private $sampleResourceData = [
        'resource' => 'bench',
        'metadata' => [
            'title'       => 'An Example Title',
            'description' => 'An Example Description'
        ]
    ];

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
        ResourceDefinitionLoader $loader3)
    {
        $this->add($loader1, ['json']);
        $this->add($loader2, ['xml']);
        $this->add($loader3, ['yml', 'yaml']);

        $loader3->load(file_get_contents($this->sample))->willReturn([$this->sampleResourceData]);

        $this->load($this->sample);
    }

    function it_sets_the_working_directory_of_loaded_resources_to_the_location_of_the_file(ResourceDefinitionLoader $yamlDefinitionLoader)
    {
        $sampleResourceData = $this->sampleResourceData;
        $sampleResourceData['metadata']['working-directory'] = realpath(dirname($this->sample));

        $yamlDefinitionLoader->load(file_get_contents($this->sample))->willReturn([$this->sampleResourceData]);

        $this->add($yamlDefinitionLoader, ['yml']);
        $this->load($this->sample)->shouldBe([$sampleResourceData]);

    }

    function it_respects_relative_working_directories_specified_in_the_metadata(ResourceDefinitionLoader $yamlDefinitionLoader)
    {
        $sampleResourceData = $this->sampleResourceData;
        $sampleResourceData['metadata']['working-directory'] = '../../';

        $yamlDefinitionLoader->load(file_get_contents($this->sample))->willReturn([$sampleResourceData]);

        $expectResourceData = $sampleResourceData;
        $expectResourceData['metadata']['working-directory'] = realpath(dirname($this->sample).'/../../');

        $this->add($yamlDefinitionLoader, ['yml']);
        $this->load($this->sample)->shouldBe([$expectResourceData]);
    }

    function it_throws_an_exception_when_an_absolute_working_directory_is_specified_in_the_metadata(ResourceDefinitionLoader $yamlDefinitionLoader)
    {
        $sampleResourceData = $this->sampleResourceData;
        $sampleResourceData['metadata']['working-directory'] = '/';

        $yamlDefinitionLoader->load(file_get_contents($this->sample))->willReturn([$sampleResourceData]);

        $this->add($yamlDefinitionLoader, ['yml']);

        $this->shouldThrow(InvalidArgumentException::class)->duringLoad($this->sample);
    }
}
