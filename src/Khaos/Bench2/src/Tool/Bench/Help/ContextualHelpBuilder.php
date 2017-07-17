<?php

namespace Khaos\Bench2\Tool\Bench\Help;

use Khaos\Bench2\Tool\Bench\Bench;
use Khaos\Bench2\Tool\Bench\Resource\Command\Command;
use Khaos\Bench2\Tool\Bench\Resource\CommandNamespace\CommandNamespace;
use Khaos\Bench2\Tool\Console\Console;
use Khaos\Bench2\Tool\Twig\Twig;
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
     * @var Console
     */
    private $console;

    /**
     * @var OptionDefinitionRepository
     */
    private $globalOptions;

    /**
     * @var Twig
     */
    private $twig;

    /**
     * ContextualHelpBuilder constructor.
     *
     * @param Bench $bench
     * @param Console $console
     * @param Twig $twig
     */
    public function __construct(Bench $bench, Console $console, Twig $twig)
    {
        $this->bench   = $bench;
        $this->console = $console;
        $this->twig    = $twig;
    }

    /**
     * @param Input $input
     */
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

        if ($context instanceof CommandNamespace) {
            $this->buildNamespaceHelpSection($context);
            return;
        }

        if ($context instanceof Command) {
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
     * @return CommandNamespace|null
     */
    private function getNamespaceFromContext($context)
    {
        $result = $this->bench->query('namespace', ['namespace' => $context]);

        if (count($result) == 1)
            return array_pop($result);

        return null;
    }

    /**
     * @param string $context
     *
     * @return Command|null
     */
    private function getCommandFromContext($context)
    {
        $parts = explode(' ', $context);

        if (count($parts) == 1)
            return null;

        $command   = array_pop($parts);
        $namespace = implode(' ', $parts);

        $result = $this->bench->query('command', [
            'namespace' => $namespace,
            'command'   => $command
        ]);

        if (count($result) == 1)
            return array_pop($result);

        return null;
    }

    private function buildNamespaceHelpSection(CommandNamespace $commandNamespace)
    {
        $values = [];
        $values['namespace'] = $commandNamespace;

        $children = [];
        $children = array_merge($children, $this->getChildNamespaces($commandNamespace->getNamespace()));
        $children = array_merge($children, $this->getChildCommands($commandNamespace->getNamespace()));

        $padding = 0;

        foreach ($children as $child)
            if (strlen($child->name) > $padding)
                $padding = strlen($child->name);

        foreach ($children as $child)
            $child->name = str_pad($child->name, $padding,' ');

        $values['commands'] = $children;
        $values['options']  = $this->buildOptionsSection($this->globalOptions);

        $this->console->write($this->twig->render(__DIR__ . '/../_assets/help/namespace.twig', $values));
    }

    private function buildCommandHelpSection(Command $command)
    {
        $values = [];
        $values['command'] = $command;
        $values['options'] = $this->buildOptionsSection($command->getOptions());
        $values['global']  = $this->buildOptionsSection($this->globalOptions);

        $this->console->write($this->twig->render(__DIR__ . '/../_assets/help/command.twig', $values));
    }

    private function getChildNamespaces($parent = null)
    {
        $namespaces = [];

        foreach ($this->bench->query('namespace') as $namespace)
        {
            /** @var CommandNamespace $namespace */
            if (!preg_match('/^'.(($parent == null) ? '' : $parent.' ').'[a-zA-Z0-9_-]+$/', $namespace->getNamespace()))
                continue;

            $parts = explode(' ', $namespace->getNamespace());
            $namespaces[end($parts)] = (object) ['name' => end($parts), 'description' => $namespace->getDescription()];
        }

        ksort($namespaces);

        return $namespaces;
    }

    private function getChildCommands($namespace = null)
    {
        $commands = [];

        foreach ($this->bench->query('command', ['namespace' => $namespace]) as $command)
        {
            /** @var Command $command */
            $commands[$command->getCommand()] = (object) ['name' => $command->getCommand(), 'description' => $command->getDescription()];
        }

        ksort($commands);

        return $commands;
    }

    private function buildOptionsSection(OptionDefinitionRepository $optionDefinitionRepository)
    {
        $padding = 0;
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

            $options[] = (object) ['name' => $name, 'description' => $optionDefinition->getDescription()];
        }

        foreach ($options as $option)
            if (strlen($option->name) > $padding)
                $padding = strlen($option->name);

        foreach ($options as $option)
            $option->name = str_pad($option->name, $padding,' ');

        return $options;
    }
}