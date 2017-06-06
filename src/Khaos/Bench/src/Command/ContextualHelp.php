<?php

namespace Khaos\Bench\Command;

use Khaos\Bench\Resource\ResourceDefinitionRepository;

class ContextualHelp
{
    /**
     * @var ResourceDefinitionRepository
     */
    private $resourceDefinitions;

    /**
     * ContextualHelp constructor.
     *
     * @param ResourceDefinitionRepository $resourceDefinitions
     */
    public function __construct(ResourceDefinitionRepository $resourceDefinitions)
    {
        $this->resourceDefinitions = $resourceDefinitions;
    }

    /**
     * @param CommandRunnerParsedEvent $event
     */
    public function __invoke(CommandRunnerParsedEvent $event)
    {
        $input = $event->getInput();

        if ($input->getOption('help')) {
            echo 'Help Screen.'."\n";
            //die();
        }
    }
}