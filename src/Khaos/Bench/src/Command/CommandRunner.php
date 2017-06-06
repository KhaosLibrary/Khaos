<?php

namespace Khaos\Bench\Command;

use Khaos\Bench\Resource\Definition\CommandDefinition;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CommandRunner
{
    /**
     * @var ResourceDefinitionRepository
     */
    private $resourceDefinitions;

    /**
     * @var CommandDefinition
     */
    private $global;
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;
    /**
     * @var UsageParserBuilder
     */
    private $usageParserBuilder;

    /**
     * CommandRunner constructor.
     *
     * @param EventDispatcher               $eventDispatcher
     * @param ResourceDefinitionRepository  $resourceDefinitions
     * @param UsageParserBuilder            $usageParserBuilder
     */
    public function __construct(EventDispatcher $eventDispatcher, ResourceDefinitionRepository $resourceDefinitions, UsageParserBuilder $usageParserBuilder)
    {
        $this->eventDispatcher     = $eventDispatcher;
        $this->usageParserBuilder  = $usageParserBuilder;
        $this->resourceDefinitions = $resourceDefinitions;

        $this->global = new CommandDefinition
        ([
            'resource' => 'bench/command',
            'metadata' => [
                'id'          => 'bench',
                'title'       => 'Bench Utility',
                'description' => 'Example description of the bench command.'
            ],
            'definition' => [
                'command' => 'bench',
                'options' => [
                    '-h, --help    Show help messages.'
                ]
            ]
        ], new OptionDefinitionParser());

    }

    public function run(array $args = [])
    {
        // Iterate over and run commands until one is successful

        foreach ($this->resourceDefinitions->findByType(CommandDefinition::TYPE) as $commandDefinition) {

            /** @var CommandDefinition $commandDefinition */

            if ($this->runCommandDefinition($args, $commandDefinition))
                return;
        }

        // Trigger error event as no command found

        $this->eventDispatcher->dispatch(
            CommandRunnerInvalidUsageEvent::NAME,
            new CommandRunnerInvalidUsageEvent($args, $this->global->getOptions())
        );
    }

    /**
     * @param array $args
     * @param CommandDefinition $commandDefinition
     *
     * @return bool True if the command definition was able to run against the supplied arguments
     */
    private function runCommandDefinition(array $args = [], CommandDefinition $commandDefinition)
    {
        // Build parser and run against args

        $options = $this->global->getOptions()->merge($commandDefinition->getOptions());
        $command = $this->global->getCommand().' '.$commandDefinition->getNamespace().' '.$commandDefinition->getCommand();
        $command = (strpos($command, '[options]') === false) ? $command.' [options]' : $command;
        $parser  = $this->usageParserBuilder->createUsageParser($command, $options);

        if (($input = $parser->parse($args)) === false)
            return false;

        // We were able to parse the given args against this command

        // TODO add event class tests
        $this->eventDispatcher->dispatch(CommandRunnerParsedEvent::NAME, new CommandRunnerParsedEvent($input));

        // Perform this commands action/handler/run

        echo $input->toJSON();

        return true;
    }
}
