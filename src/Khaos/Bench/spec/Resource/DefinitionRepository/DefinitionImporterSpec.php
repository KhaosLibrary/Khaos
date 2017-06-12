<?php

namespace spec\Khaos\Bench\Resource\DefinitionRepository;

use Exception;
use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\DefinitionLoader\GlobPatternDefinitionLoader;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionImporter;
use Khaos\Bench\Resource\DefinitionRepository\Event\ResourceDefinitionImportedEvent;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use PhpSpec\ObjectBehavior;

class DefinitionImporterSpec extends ObjectBehavior
{
    function let(Bench $bench)
    {
        $this->beConstructedWith($bench);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefinitionImporter::class);
    }

    function it_imports_files_matching_the_given_pattern_to_the_bench(ResourceDefinitionImportedEvent $event, ImportDefinition $importDefinition, Bench $bench)
    {
        $event->getResourceDefinition()->willReturn($importDefinition);

        $importDefinition->getWorkingDirectory()->willReturn($workingDirectory = realpath(__DIR__.'/../_sample/glob'));
        $importDefinition->getImportPatterns()->willReturn(['*.yml']);

        $this($event);

        $bench->import($workingDirectory.'/file1.yml')->shouldHaveBeenCalled();
        $bench->import($workingDirectory.'/file2.yml')->shouldHaveBeenCalled();
    }

    function it_throws_an_exception_if_the_definition_specified_is_not_of_type_import(
        ResourceDefinitionImportedEvent $event,
        ResourceDefinition $unknownResourceDefinition)
    {
        $event->getResourceDefinition()->willReturn($unknownResourceDefinition);
        $this->shouldThrow(Exception::class)->during('__invoke', [$event]);
    }
}
