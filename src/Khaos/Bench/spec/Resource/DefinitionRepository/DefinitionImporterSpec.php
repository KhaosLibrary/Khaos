<?php

namespace spec\Khaos\Bench\Resource\DefinitionRepository;

use Exception;
use Khaos\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\DefinitionLoader\GlobPatternDefinitionLoader;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionImporter;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionRepositoryImportEvent;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use PhpSpec\ObjectBehavior;

class DefinitionImporterSpec extends ObjectBehavior
{
    function let(ResourceDefinitionRepository $resourceDefinitionRepository, GlobPatternDefinitionLoader $globPatternDefinitionLoader)
    {
        $this->beConstructedWith($resourceDefinitionRepository, $globPatternDefinitionLoader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefinitionImporter::class);
    }

    function it_adds_resource_definitions_it_finds_from_the_import_resource_to_the_repository(
        ResourceDefinitionRepository $resourceDefinitionRepository,
        GlobPatternDefinitionLoader $globPatternDefinitionLoader,
        DefinitionRepositoryImportEvent $event,
        ImportDefinition $importDefinition,
        ResourceDefinition $resourceDefinition)
    {
        $event->getResourceDefinition()->willReturn($importDefinition);
        $importDefinition->getImportPatterns()->willReturn(['manifest.yml']);
        $globPatternDefinitionLoader->load(['manifest.yml'])->willReturn([$resourceDefinition]);

        $this($event);

        $resourceDefinitionRepository->import($resourceDefinition)->shouldBeCalled();
    }

    function it_throws_an_exception_if_the_definition_specified_is_not_of_type_import(
        DefinitionRepositoryImportEvent $event,
        ResourceDefinition $unknownResourceDefinition)
    {
        $event->getResourceDefinition()->willReturn($unknownResourceDefinition);
        $this->shouldThrow(Exception::class)->during('__invoke', [$event]);
    }
}
