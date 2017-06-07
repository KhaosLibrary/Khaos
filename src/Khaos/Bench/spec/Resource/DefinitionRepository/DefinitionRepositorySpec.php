<?php

namespace spec\Khaos\Bench\Resource\DefinitionRepository;

use InvalidArgumentException;
use Khaos\Bench\Resource\Definition\BenchDefinition;
use Khaos\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionRepositoryImportEvent;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DefinitionRepositorySpec extends ObjectBehavior
{
    function let(EventDispatcher $eventDispatcher, ResourceDefinitionLoader $resourceDefinitionLoader, ResourceDefinition $resource)
    {
        $this->beConstructedWith($eventDispatcher, $resourceDefinitionLoader);

        $resource->getId()->willReturn('example');
        $resource->getType()->willReturn('example');
    }

    function it_allows_resources_to_be_added_to_the_repository(ResourceDefinition $resource)
    {
        $this->import($resource);
        $this->findById('example')->shouldReturn($resource);
    }

    function it_throws_invalid_argument_exception_when_a_resource_to_be_added_uses_an_existing_id(ResourceDefinition $resource)
    {
        $this->import($resource);
        $this->shouldThrow(InvalidArgumentException::class)->duringImport($resource);
    }

    function it_allows_find_by_id(ResourceDefinition $resource)
    {
        $this->import($resource);
        $this->findById('example')->shouldReturn($resource);
    }

    function it_provides_null_find_by_id_has_no_match()
    {
        $this->findById('does-not-exist')->shouldReturn(null);
    }

    function it_allows_find_by_callback(ResourceDefinition $resource)
    {
        $this->import($resource);
        $this->query(function(ResourceDefinition $resourceDefinition) { return $resourceDefinition->getId() == 'example'; })->shouldBe([$resource]);
    }

    function it_allows_find_by_type(BenchDefinition $applicationResource, ImportDefinition $importResource)
    {
        $applicationResource->getId()->willReturn('application-1');
        $applicationResource->getType()->willReturn(BenchDefinition::TYPE);

        $importResource->getId()->willReturn('import-1');
        $importResource->getType()->willReturn(ImportDefinition::TYPE);

        $this->import($applicationResource);
        $this->import($importResource);

        $this->findByType(ImportDefinition::TYPE)->shouldReturn(['import-1' => $importResource]);
    }

    function it_dispatches_a_resource_imported_event_after_import(EventDispatcher $eventDispatcher, ImportDefinition $importResource)
    {
        $importResource->getId()->willReturn('import-1');
        $importResource->getType()->willReturn(ImportDefinition::TYPE);

        $this->import($importResource);

        $eventDispatcher->dispatch(
            Argument::is(DefinitionRepositoryImportEvent::NAME),
            Argument::type(DefinitionRepositoryImportEvent::class)
        )->shouldBeCalled();
    }

    function it_dispatches_a_resource_imported_event_of_resource_type_after_import(EventDispatcher $eventDispatcher, ImportDefinition $importResource)
    {
        $importResource->getId()->willReturn('import-1');
        $importResource->getType()->willReturn(ImportDefinition::TYPE);

        $this->import($importResource);

        $eventDispatcher->dispatch(
            Argument::is(DefinitionRepositoryImportEvent::NAME.'::'.ImportDefinition::TYPE),
            Argument::type(DefinitionRepositoryImportEvent::class)
        )->shouldBeCalled();
    }

    function it_uses_definition_loader_when_importing_resource_definition_which_is_not_of_type_resource_definition(ResourceDefinitionLoader $resourceDefinitionLoader, ResourceDefinition $resource)
    {
        $sample = [
            'resource' => 'bench',
            'metadata' => [
                'name'        => 'test',
                'description' => 'test'
            ]
        ];

        $resourceDefinitionLoader->load($sample)->willReturn([$resource]);

        $this->import($sample);

        $resourceDefinitionLoader->load($sample)->shouldHaveBeenCalled();
    }

    function it_will_throw_invalid_argument_exception_when_source_of_import_is_not_supported(ResourceDefinitionLoader $resourceDefinitionLoader)
    {
        $sample = [
            'resource' => 'bench',
            'metadata' => [
                'name'        => 'test',
                'description' => 'test'
            ]
        ];

        $resourceDefinitionLoader->load($sample)->willReturn(null);

        $this->shouldThrow(InvalidArgumentException::class)->duringImport($sample);
    }
}
