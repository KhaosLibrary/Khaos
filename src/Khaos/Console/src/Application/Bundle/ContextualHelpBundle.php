<?php

namespace Khaos\Console\Application\Bundle;

use Aura\Di\Container;
use Khaos\Console\Application\Application;
use Khaos\Console\Application\Context;
use Khaos\Console\Application\Event\BeforeActionEvent;
use Khaos\Console\Application\Event\InvalidUsageEvent;
use Khaos\Console\Usage\Model\OptionDefinition;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;

class ContextualHelpBundle implements Bundle
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @inheritDoc
     */
    public function define(Container $di)
    {
        return;
    }

    /**
     * @inheritDoc
     */
    public function modify(Container $di)
    {
        return;
    }

    /**
     * @inheritDoc
     */
    public function setup(Application $application)
    {
        $this->application = $application
            ->option('-h, --help  Show help message.')
            ->on(Application::EVENT_BEFORE_ACTION, function (BeforeActionEvent $event) {
            
                if ($event->getInput()->getOption('help') == true) {
                    $this->showHelpPage($event->getContext());
                    $event->preventAction();
                }
            })
            ->on(Application::EVENT_INVALID_USAGE, function (InvalidUsageEvent $event) {
            
                $input    = $event->getInputSequence();
                $contexts = $this->application->getContexts();
                $command  = [];

                while ($positional = $input->pop()) {
                    $command[] = $positional;
                }

                $key = implode(' ', $command);

                if (isset($contexts[$key])) {
                    $this->showHelpPage($contexts[$key]['instance']);
                } else {
                    $this->showHelpPage($this->application->getRootContext());
                }
            });
    }

    public function showHelpPage(Context $context)
    {
        // Description

        echo "\n\033[1m". ($context->getDescription()?:$context->getName()) . "\033[0m\n\n";

        // Usage

        if (count($context->getUsageDefinitions()) > 0) {
            echo "\033[33mUsage:\033[0m\n";

            foreach (array_keys($context->getUsageDefinitions()) as $usage) {
                echo "  {$usage}\n";
            }

            echo "\n";
        }

        // Command Options

        if ($context !== $this->application->getRootContext()) {
            $this->displayOptionsHelp("\033[33mCommand Options:\033[0m", $context->getOptionDefinitions());
        }

        // Sub Commands

        $this->displaySubCommandHelp($context);

        // Global Options
        $this->displayOptionsHelp("\033[33mGlobal Options:\033[0m", $this->application->getRootContext()->getOptionDefinitions());
    }

    private function displaySubCommandHelp(Context $context)
    {
        $contexts = $this->application->getContexts();
        $lines    = [];
        $padding  = 0;

        foreach ($contexts[$context->getName()]['children'] as $child) {
        /** @var Context $child */
            $name    = $child->getName();
            $lines[] = ['name' => substr($name, strrpos($name, ' ')), 'description' => $child->getDescription()];

            if (($length = strlen($name)) > $padding) {
                $padding = $length;
            }
        }

        if (!empty($lines)) {
            echo "\033[33mSub Commands:\033[0m\n";
            $padding += 4;

            foreach ($lines as $line) {
                echo '  '."\033[32m".str_pad($line['name'], $padding) . "\033[0m" . $line['description'] . "\n";
            }

            echo "\n";
        }
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
