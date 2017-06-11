<?php

namespace Khaos\Bench\Resource\DefinitionRepository;

use Exception;
use Khaos\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\DefinitionLoader\GlobPatternDefinitionLoader;
use Khaos\Bench\Resource\ResourceDefinitionRepository;

class DefinitionImporter
{
    /**
     * @var ResourceDefinitionRepository
     */
    private $definitionRepository;

    /**
     * @var GlobPatternDefinitionLoader
     */
    private $globDefinitionLoader;

    public function __construct(
        ResourceDefinitionRepository $definitionRepository,
        GlobPatternDefinitionLoader $definitionLoader)
    {
        $this->definitionRepository = $definitionRepository;
        $this->globDefinitionLoader     = $definitionLoader;
    }

    public function __invoke(DefinitionRepositoryImportEvent $event)
    {
        $importDefinition = $event->getResourceDefinition();

        if (!$importDefinition instanceof ImportDefinition)
            throw new Exception("Only ResourceDefinitions of type '".ImportDefinition::TYPE."' accepted, got '".$importDefinition->getType()."'.");

        foreach ($importDefinition->getImportPatterns() as $globPattern)
            foreach ($this->globDefinitionLoader->load($importDefinition->getWorkingDirectory().'/'.$globPattern) as $resourceDefinition)
                $this->definitionRepository->import($resourceDefinition);
    }
}