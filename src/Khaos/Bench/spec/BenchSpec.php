<?php

namespace spec\Khaos\Bench;

use Auryn\Injector;
use Khaos\Bench\Command\CommandRunner;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use Khaos\Bench\Tool\Bench\BenchTool;
use PhpSpec\ObjectBehavior;

class BenchSpec extends ObjectBehavior
{
    private $sampleBenchCallLocation = __DIR__.'/_sample/nested/directory';

    private $sample = [
        'resource' => 'bench',
        'metadata' => [
            'id'          => 'bench',
            'title'       => 'Example Title',
            'description' => 'Example Description'
        ]
    ];

    function let(CommandRunner $commandRunner, ResourceDefinitionLoader $definitionLoader, Injector $injector)
    {
        $this->beConstructedWith($commandRunner, $injector, $definitionLoader);
    }

    function it_allows_resource_definitions_to_be_imported(ResourceDefinitionLoader $definitionLoader, Injector $injector, BenchTool $benchTool)
    {
        $injector->make(BenchTool::class)->willReturn($benchTool);
        $definitionLoader->load('bench.yml')->willReturn([$this->sample]);

        $this->import('bench.yml');

        $benchTool->import($this->sample)->shouldHaveBeenCalled();
    }

    function it_provides_a_helper_to_find_the_root_bench_resource_definition_file()
    {
        $this::getRootResourceDefinition($this->sampleBenchCallLocation, 'bench.yml')->shouldBe(__DIR__.'/_sample/bench.yml');
    }

    function it_default_to_help_when_no_command_specified(CommandRunner $commandRunner)
    {
        $this->run(['bench']);
        $commandRunner->run(['bench', '--help'])->shouldHaveBeenCalled();
    }
}
