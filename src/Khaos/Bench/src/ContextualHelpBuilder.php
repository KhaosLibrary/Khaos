<?php

namespace Khaos\Bench;

use Khaos\Bench\Resource\Definition\CommandDefinition;
use Khaos\Bench\Resource\Definition\CommandNamespaceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Console\Usage\Input;
use Khaos\Console\Usage\Model\OptionDefinition;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;

class ContextualHelpBuilder
{
    /**
     * @var ResourceDefinitionRepository
     */
    private $resourceDefinitions;

    /**
     * @var OptionDefinitionRepository
     */
    private $globalOptions;

    public function __construct(ResourceDefinitionRepository $resourceDefinitions)
    {
        $this->resourceDefinitions = $resourceDefinitions;
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

        if ($context instanceof CommandNamespaceDefinition) {
            $this->buildNamespaceHelpSection($context);
            return;
        }

        if ($context instanceof CommandDefinition) {
            $this->buildCommandHelpSection($context);
            return;
        }
    }

    public function getCommandUsage(CommandDefinition $commandDefinition)
    {
        return [$commandDefinition->getCommand(), $commandDefinition->getTitle()];
    }

    public function getCommandOptions(OptionDefinitionRepository $optionDefinitionRepository)
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

    public function getChildNamespaces($parent = null)
    {
        $namespaces = [];

        foreach ($this->resourceDefinitions->findByType(CommandNamespaceDefinition::TYPE) as $namespaceDefinition)
        {
            /** @var CommandNamespaceDefinition $namespaceDefinition */

            if (!preg_match('/^'.(($parent == null) ? '' : $parent.' ').'[a-zA-Z0-9_-]+$/', $namespaceDefinition->getNamespace()))
                continue;

            $parts = explode(' ', $namespaceDefinition->getNamespace());
            $namespaces[end($parts)] = $namespaceDefinition->getTitle();
        }

        ksort($namespaces);

        return $namespaces;
    }

    public function getChildCommands($namespace = null)
    {
        $commands = [];

        foreach ($this->resourceDefinitions->findByType(CommandDefinition::TYPE) as $commandDefinition)
        {
            /** @var CommandDefinition $commandDefinition */

            if ($commandDefinition->getNamespace() != $namespace)
                continue;

            $commands[$commandDefinition->getCommand()] = $commandDefinition->getTitle();
        }

        ksort($commands);

        return $commands;
    }

    public function getHelpContext($context)
    {
        foreach ($this->resourceDefinitions->findByType(CommandNamespaceDefinition::TYPE) as $namespaceDefinition)
        {
            /** @var CommandNamespaceDefinition $namespaceDefinition */

            if ($namespaceDefinition->getNamespace() == $context)
                return $namespaceDefinition;
        }

        foreach ($this->resourceDefinitions->findByType(CommandDefinition::TYPE) as $commandDefinition)
        {
            /** @var CommandDefinition $commandDefinition */

            $namespace = $commandDefinition->getNamespace();

            if (((($namespace)?$namespace.' ':'').$commandDefinition->getCommand()) == $context)
                return $commandDefinition;
        }

        return null;
    }

    private function buildNamespaceHelpSection(CommandNamespaceDefinition $namespaceDefinition)
    {
        // Title
        $title = $namespaceDefinition->getTitle();
        if ($title) echo "\n\e[1m".$title."\e[0m\n";

        // Description
        $description = $namespaceDefinition->getDescription();
        if ($description) echo "\e[0m".$description."\e[0m\n";

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

        echo "\n\e[1mCommands:\e[0m\n";

        foreach ($children as $k => $v)
            echo '    '.str_pad($k, $padding, ' ').$v."\n";

        // Global Options

        $this->buildOptionsSection("Global Options", $this->globalOptions);

        echo "\n";
    }

    private function buildCommandHelpSection(CommandDefinition $commandDefinition)
    {
        // Title
        $title = $commandDefinition->getTitle();
        if ($title) echo "\n\e[1m".$title."\e[0m\n";

        // Description
        $description = $commandDefinition->getDescription();
        if ($description) echo "\e[0m".$description."\e[0m\n";

        // Usage
        echo "\n\e[1mUsage:\e[0m\n";
        echo "  ".$commandDefinition->getUsage()."\n";

        // Command Options
        if (count($commandDefinition->getOptions()) > 0)
            $this->buildOptionsSection("Command Options", $commandDefinition->getOptions());

        // Global Options
        if (count($this->globalOptions) > 0)
            $this->buildOptionsSection("Global Options", $this->globalOptions);

        echo "\n";
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

        echo "\n\e[1m".$title.":\e[0m\n";

        foreach ($options as $option)
        {
            echo '    '.str_pad($option[0], $padding, ' ').$option[1]."\n";
        }
    }
}
