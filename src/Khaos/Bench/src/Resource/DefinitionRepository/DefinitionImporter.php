<?php

namespace Khaos\Bench\Resource\DefinitionRepository;

use Exception;
use InvalidArgumentException;
use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\DefinitionRepository\Event\ResourceDefinitionImportedEvent;

class DefinitionImporter
{
    /**
     * @var Bench
     */
    private $bench;

    /**
     * DefinitionImporter constructor.
     *
     * @param Bench  $bench
     */
    public function __construct(Bench $bench)
    {
        $this->bench = $bench;
    }

    /**
     * @param ResourceDefinitionImportedEvent $event
     * @throws Exception
     */
    public function __invoke(ResourceDefinitionImportedEvent $event)
    {
        $importDefinition = $event->getResourceDefinition();

        if (!$importDefinition instanceof ImportDefinition)
            throw new Exception("Only ResourceDefinitions of type '".ImportDefinition::TYPE."' accepted, got '".$importDefinition->getType()."'.");

        foreach ($importDefinition->getImportPatterns() as $pattern)
        {
            $files = glob($pattern = $importDefinition->getWorkingDirectory().'/'.$pattern);

            if ($files === false)
                throw new InvalidArgumentException("Pattern '{$pattern}' is not valid.");

            foreach ($files as $file)
                $this->bench->import($file);
        }
    }
}