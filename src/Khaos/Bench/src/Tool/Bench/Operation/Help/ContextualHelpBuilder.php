<?php

namespace Khaos\Bench\Tool\Bench\Operation\Help;

use Khaos\Bench\Bench;
use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Definition\DefinitionRepository;
use Khaos\Bench\Tool\Bench\Resource\Command\BenchCommandDefinition;
use Khaos\Bench\Tool\Bench\Resource\CommandNamespace\BenchCommandNamespaceDefinition;
use Khaos\Bench\Tool\Console\ConsoleToolOperationProxy;
use Khaos\Console\Usage\Input;
use Khaos\Console\Usage\Model\OptionDefinition;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;

class ContextualHelpBuilder
{
    /**
     * @var Bench
     */
    private $bench;

    /**
     * @var OptionDefinitionRepository
     */
    private $globalOptions;

    /**
     * @var DefinitionRepository
     */
    private $definitions;

    /**
     * @var ConsoleToolOperationProxy
     */
    private $console;

    /**
     * ContextualHelpBuilder constructor.
     *
     * @param Bench $bench
     */
    public function __construct(Bench $bench)
    {
        $this->bench       = $bench;
        $this->console     = $bench->tool('console');
        $this->definitions = $this->bench->getDefinitionRepository();
    }

    public function build(Input $input)
    {
        $globalOptions = new OptionDefinitionRepository();
        $globalOptions->add(new OptionDefinition('h', 'help', 'Get detailed help.', OptionDefinition::TYPE_BOOL));
        $globalOptions->add(new OptionDefinition('v', 'verbose', 'Give extra detail when running a command.', OptionDefinition::TYPE_BOOL));

        $this->globalOptions = $globalOptions;

        $context = $input->getArgument('command');

        if (is_array($context))
            $context = implode(' ', $context);

        $context = (!$context) ? 'bench' : 'bench '.$context;
        $context = $this->getHelpContext($context);

        if ($context instanceof BenchCommandNamespaceDefinition) {
            $this->buildNamespaceHelpSection($context);
            return;
        }

        if ($context instanceof BenchCommandDefinition) {
            $this->buildCommandHelpSection($context);
            return;
        }
    }

    private function getHelpContext($context)
    {
        if ($definition = $this->getNamespaceFromContext($context))
            return $definition;


        if ($definition = $this->getCommandFromContext($context))
            return $definition;

        return null;
    }

    /**
     * @param string $context
     *
     * @return Definition|null
     */
    private function getNamespaceFromContext($context)
    {
        $result = $this->definitions->query
        ([
            'schema' => 'bench/command-namespace',
            'definition' => [
                'namespace' => $context
            ]
        ]);

        if (count($result) == 1)
            return array_pop($result);

        return null;
    }

    /**
     * @param $context
     * @return Definition|null
     */
    private function getCommandFromContext($context)
    {
        $parts = explode(' ', $context);

        if (count($parts) == 1)
            return null;

        $command = array_pop($parts);
        $namespace = implode(' ', $parts);

        $result = $this->definitions->query
        ([
            'schema'     => 'bench/command',
            'definition' => [
                'namespace' => $namespace,
                'command' => $command
            ]
        ]);

        if (count($result) == 1)
            return array_pop($result);

        return null;
    }

    private function getCommandOptions(OptionDefinitionRepository $optionDefinitionRepository)
    {
        $options = [];

        foreach ($optionDefinitionRepository as $optionDefinition)
        {
            /** @var OptionDefinition $optionDefinition */

            // Option Name

            $names = [];

            if (($shortName = $optionDefinition->getShortName()) !== null)
                $names[] = '-'.$shortName;

            if (($longName = $optionDefinition->getLongName()) !== null)
                $names[] = '--'.$longName;

            if ($optionDefinition->getType() == OptionDefinition::TYPE_VALUE)
                $names[count($names) - 1] .= '=<'.$optionDefinition->getArgument().'>';

            $name = implode(', ', $names);

            // Option Description

            $options[] = [$name, $optionDefinition->getDescription()];
        }

        return $options;
    }

    private function getChildNamespaces($parent = null)
    {
        $namespaces = [];

        foreach ($this->definitions->query(['schema' => 'bench/command-namespace']) as $namespaceDefinition)
        {
            /** @var BenchCommandNamespaceDefinition $namespaceDefinition */
            if (!preg_match('/^'.(($parent == null) ? '' : $parent.' ').'[a-zA-Z0-9_-]+$/', $namespaceDefinition->getNamespace()))
                continue;

            $parts = explode(' ', $namespaceDefinition->getNamespace());
            $namespaces[end($parts)] = $namespaceDefinition->getTitle();
        }

        ksort($namespaces);

        return $namespaces;
    }


    private function getChildCommands($namespace = null)
    {
        $query    = ['schema' => 'bench/command', 'definition' => ['namespace' => $namespace]];
        $commands = [];

        foreach ($this->definitions->query($query) as $commandDefinition)
        {
            /** @var BenchCommandDefinition $commandDefinition */
            $commands[$commandDefinition->getCommand()] = $commandDefinition->getTitle();
        }

        ksort($commands);

        return $commands;
    }

    private function buildNamespaceHelpSection(BenchCommandNamespaceDefinition $namespaceDefinition)
    {
        $this->console->write("");

        // Title
        $title = $namespaceDefinition->getTitle();
        if ($title) $this->console->write( "<heading>{$title}</heading>");

        // Description
        $description = $namespaceDefinition->getDescription();
        if ($description) $this->console->write($description);

        // Sub Commands
        $padding = 0;

        $children = [];
        $children = array_merge($children, $this->getChildNamespaces($namespaceDefinition->getNamespace()));
        $children = array_merge($children, $this->getChildCommands($namespaceDefinition->getNamespace()));

        ksort($children);

        foreach ($children as $k => $v)
            if (strlen($k) > $padding)
                $padding = strlen($k);

        $padding += 8;
        $this->console->write("");
        $this->console->write("<heading>Commands</heading>");

        foreach ($children as $k => $v)
            $this->console->write('    <green>'.str_pad($k, $padding, ' ').'</green>'.$v);

        // Global Options
        $this->console->write("");
        $this->buildOptionsSection("Global Options", $this->globalOptions);
        $this->console->write("");
    }

    private function buildCommandHelpSection(BenchCommandDefinition $commandDefinition)
    {
        $this->console->write("");

        // Title
        $title = $commandDefinition->getTitle();
        if ($title) $this->console->write("<heading>{$title}</heading>");

        // Description
        $description = $commandDefinition->getDescription();
        if ($description) $this->console->write($description);

        // Usage
        $this->console->write("");
        $this->console->write("<heading>Usage</heading>");
        $this->console->write("  ".$commandDefinition->getUsage());

        // Command Options
        if (count($commandDefinition->getOptions()) > 0) {
            $this->console->write("");
            $this->buildOptionsSection("Command Options", $commandDefinition->getOptions());
        }

        // Global Options
        if (count($this->globalOptions) > 0) {
            $this->console->write("");
            $this->buildOptionsSection("Global Options", $this->globalOptions);
        }

        $this->console->write("");
    }

    private function buildOptionsSection($title, OptionDefinitionRepository $optionDefinitionRepository)
    {
        $options = $this->getCommandOptions($optionDefinitionRepository);
        $padding = 0;

        foreach ($options as $option) {
            if (strlen($option[0]) > $padding)
                $padding = strlen($option[0]);
        }

        $padding += 8;
        $this->console->write("<heading>{$title}</heading>");

        foreach ($options as $option)
        {
            $this->console->write('    <green>'.str_pad($option[0], $padding, ' ').'</green>'.$option[1]);
        }
    }
}