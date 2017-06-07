<?php

namespace Khaos\Bench;

use Khaos\Bench\Command\CommandRunner;
use Khaos\Bench\Resource\Definition\BenchDefinition;
use Khaos\Bench\Resource\Definition\CommandDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Bench\Tool\ToolFactory;
use Khaos\Console\Usage\Input;
use Khaos\Console\Usage\Model\Option;
use Khaos\Console\Usage\Model\OptionDefinition;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Bench
{
    const VERSION = 'Bench 0.0.2';

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var ResourceDefinitionRepository
     */
    private $definitions;

    /**
     * @var ToolFactory
     */
    private $toolFactory;

    /**
     * @var CommandRunner
     */
    private $commandRunner;

    /**
     * @var ResourceDefinitionFieldParser
     */
    private $definitionFieldParser;

    /**
     * Bench constructor.
     *
     * @param EventDispatcher $eventDispatcher
     * @param ResourceDefinitionRepository $resourceDefinitionRepository
     * @param ToolFactory $toolFactory
     * @param CommandRunner $commandRunner
     * @param ResourceDefinitionFieldParser $definitionFieldParser
     */
    public function __construct(EventDispatcher $eventDispatcher, ResourceDefinitionRepository $resourceDefinitionRepository, ToolFactory $toolFactory, CommandRunner $commandRunner, ResourceDefinitionFieldParser $definitionFieldParser)
    {
        $this->eventDispatcher       = $eventDispatcher;
        $this->definitions           = $resourceDefinitionRepository;
        $this->toolFactory           = $toolFactory;
        $this->commandRunner         = $commandRunner;
        $this->definitionFieldParser = $definitionFieldParser;
    }

    /**
     * @param $source
     */
    public function import($source)
    {
        $this->definitions->import($source);
    }

    // bench [options] <command>
    public function run(array $args = [])
    {
        $this->prepareBenchTools();
        $this->commandRunner->run($args);
    }

    public static function getRootResourceDefinition($search, $file = 'bench.yml')
    {
        $search = explode('/', substr($search, 1));

        for ($i = count($search); $i > 0; --$i)
        {
            $candidate = '/' . implode('/', array_slice($search, 0, $i)) . '/' . $file;

            if (file_exists($candidate))
                return $candidate;
        }

        return [
            'resource' => BenchDefinition::TYPE,
            'metadata' => [
                'title' => 'Global Bench',
                'description' => 'Bench is running in the global scope of the system.'
            ],
            'tools' => []
        ];
    }

    private function prepareBenchTools()
    {
        $this->definitionFieldParser->addValue('bench', $this);

        /** @var BenchDefinition[] $benchDefinitions */
        $benchDefinitions = $this->definitions->findByType(BenchDefinition::TYPE);

        foreach ($benchDefinitions as $benchDefinition)
            foreach ($benchDefinition->getTools() as $tool)
                $this->definitionFieldParser->addValue($tool, $this->toolFactory->create($tool));
    }

    public function version()
    {
        return self::VERSION;
    }

    public function help(Input $input)
    {
        $globalOptions = new OptionDefinitionRepository();
        $globalOptions->add(new OptionDefinition('h', 'help', 'Show help for the given command.', OptionDefinition::TYPE_BOOL));

        $padding = 16;

        // Show Root Help (ie. list of namespaces and commands in alphabetical order)

        echo "\n\e[1mBench\e[0m\e[32m 0.0.1\e[0m\n\n";
        echo "\e[1mUsage:\e[0m\n";
        echo "    bench <command> [options]\n\n";

        $namespace = null;

        /** @var CommandDefinition[] $commands */
        $commands = $this->definitions->findByType(CommandDefinition::TYPE);

        echo "\e[1mSub Commands:\e[0m\n";

        foreach ($commands as $command) {

            if ($command->getNamespace() != $namespace)
                continue;

            echo '  '."\033[32m".str_pad($command->getCommand(), $padding) . "\033[0m" . $command->getTitle() . "\n\n";
        }

        // Show Help for a specific namespace

        // Show help for a specific command

        // Show global options

        $this->displayOptionsHelp("\033[1mGlobal Options:\033[0m", $globalOptions);
    }

    private function displayOptionsHelp($heading, OptionDefinitionRepository $repository)
    {
        $padding = 0;
        $lines   = [];

        foreach ($repository as $option) {
            /** @var OptionDefinition $option */

            $definition  = '  ';
            $description = $option->getDescription();

            $definition .= $option->getShortName()?'-'.$option->getShortName().', ':'    ';
            $definition .= $option->getLongName()?'--'.$option->getLongName():'';
            $definition .= $option->getType() == OptionDefinition::TYPE_VALUE ? '=<'.$option->getArgument().'>':'';

            if (($length = strlen($definition)) > $padding) {
                $padding = $length;
            }

            $lines[] = ['definition' => $definition, 'description' => $description];
        }

        $padding += 4;

        if (!empty($lines)) {
            echo $heading."\n";

            foreach ($lines as $line) {
                echo "\033[32m".str_pad($line['definition'], $padding) ."\033[0m". $line['description'] . "\n";
            }

            echo "\n";
        }
    }
}
