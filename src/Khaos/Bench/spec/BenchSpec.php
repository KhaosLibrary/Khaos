<?php

namespace spec\Khaos\Bench;

use Khaos\Bench\Bench;
use Khaos\Bench\Resource\Definition\BenchDefinition;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Bench\Tool\ToolFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BenchSpec extends ObjectBehavior
{
    private $sampleBenchCallLocation = __DIR__.'/_sample/nested/directory';

    function let(EventDispatcher $eventDispatcher, ResourceDefinitionRepository $resourceDefinitionRepository, ToolFactory $toolFactory)
    {
        $this->beConstructedWith($eventDispatcher, $resourceDefinitionRepository, $toolFactory);
    }

    function it_allows_resource_definitions_to_be_imported(ResourceDefinitionRepository $resourceDefinitionRepository)
    {
        $this->import('bench.yml');
        $resourceDefinitionRepository->import('bench.yml')->shouldHaveBeenCalled();
    }

    function it_provides_a_helper_to_find_the_root_bench_resource_definition_file()
    {
        $this::getRootResourceDefinition($this->sampleBenchCallLocation, 'bench.yml')->shouldBe(__DIR__.'/_sample/bench.yml');
    }

    function it_provides_a_default_bench_resource_when_none_can_be_found()
    {
        $this::getRootResourceDefinition('/', 'bench.yml')->shouldBe([
            'resource' => BenchDefinition::TYPE,
            'metadata' => [
                'title' => 'Global Bench',
                'description' => 'Bench is running in the global scope of the system.'
            ],
            'tools' => []
        ]);
    }

    function it_initialises_the_required_tools_on_bench_run(BenchDefinition $benchDefinition, ToolFactory $toolFactory, ResourceDefinitionRepository $resourceDefinitionRepository)
    {
        $resourceDefinitionRepository->findByType(BenchDefinition::TYPE)->willReturn([$benchDefinition]);
        $benchDefinition->getTools()->willReturn(['docker']);
        $this->run();
        $toolFactory->create('docker')->shouldBeCalled();
    }
}
