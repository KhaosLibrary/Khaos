<?php

namespace Khaos\Bench\Command;

use Khaos\Bench\Command\Event\CommandFoundEvent;
use Khaos\Bench\Command\Event\InvalidUsageEvent;
use Khaos\Bench\Tool\Bench\Resource\Definition\CommandDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use Khaos\Console\Usage\Parser\UsageParser;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

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
     * @var ResourceDefinitionFieldParser
     */
    private $fieldParser;

    /**
     * CommandRunner constructor.
     *
     * @param EventDispatcher $eventDispatcher
     * @param ResourceDefinitionRepository $resourceDefinitions
     * @param UsageParserBuilder $usageParserBuilder
     */
    public function __construct(EventDispatcher $eventDispatcher, ResourceDefinitionRepository $resourceDefinitions, UsageParserBuilder $usageParserBuilder, ResourceDefinitionFieldParser $fieldParser)
    {
        $this->eventDispatcher     = $eventDispatcher;
        $this->usageParserBuilder  = $usageParserBuilder;
        $this->resourceDefinitions = $resourceDefinitions;
        $this->fieldParser         = $fieldParser;

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
        foreach ($this->resourceDefinitions->findByType(CommandDefinition::TYPE) as $commandDefinition) {

            /** @var CommandDefinition $commandDefinition */

            if (($input = $this->runAgainstCommandDefinition($args, $commandDefinition)) === false)
                continue;

            $this->runCommandTasks($commandDefinition, $input);

            return;
        }

        $this->eventDispatcher->dispatch(InvalidUsageEvent::NAME,  new InvalidUsageEvent($args, $this->global->getOptions()));
    }

    /**
     * @param array $args
     * @param CommandDefinition $commandDefinition
     *
     * @return bool True if the command definition was able to run against the supplied arguments
     */
    private function runAgainstCommandDefinition(array $args = [], CommandDefinition $commandDefinition)
    {
        if (($input = $this->buildUsageParser($commandDefinition)->parse($args)) === false)
            return false;

        $this->eventDispatcher->dispatch(CommandFoundEvent::NAME, new CommandFoundEvent($input));

        return $input;
    }

    /**
     * @param CommandDefinition $commandDefinition
     *
     * @return UsageParser
     */
    private function buildUsageParser(CommandDefinition $commandDefinition)
    {
        $usage   = $commandDefinition->getUsage();
        $options = $this->global->getOptions()->merge($commandDefinition->getOptions());

        return $this->usageParserBuilder->createUsageParser($usage, $options);
    }

    /**
     * @param CommandDefinition $commandDefinition
     * @param $input
     */
    private function runCommandTasks(CommandDefinition $commandDefinition, $input)
    {
        $tasks  = $commandDefinition->getRun();
        $values = ['input' => $input];

        if (!is_array($tasks))
            $tasks = [$tasks];

        foreach ($tasks as $task)
            echo $this->fieldParser->evaluate($task, $values);
    }
}
