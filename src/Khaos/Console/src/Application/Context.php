<?php

namespace Khaos\Console\Application;

use Khaos\Console\Application\Event\BeforeActionEvent;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Context
{
    private $context;
    private $description;
    private $usage = [];
    private $actions = [];
    private $optionParser;
    private $inherit;
    private $optionRepository;
    private $dispatcher;
    private $usageParserBuilder;

    public function __construct($context, EventDispatcher $eventDispatcher, Context $inherit = null, OptionDefinitionParser $optionParser, UsageParserBuilder $usageParserBuilder)
    {
        $this->context            = $context;
        $this->optionParser       = $optionParser;
        $this->optionRepository   = new OptionDefinitionRepository();
        $this->inherit            = $inherit;
        $this->dispatcher         = $eventDispatcher;
        $this->usageParserBuilder = $usageParserBuilder;
    }

    public function getName()
    {
        return $this->context;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getUsageDefinitions()
    {
        return $this->usage;
    }

    public function getOptionDefinitions()
    {
        return $this->optionRepository;
    }

    public function description($description)
    {
        $this->description = $description;
        return $this;
    }

    public function usage($usage, $alias = null)
    {
        $usage = (strpos($usage, '[options]') === false) ? $usage.' [options]' : $usage;
        $this->usage[$usage] = $alias;

        return $this;
    }

    public function option($option)
    {
        $this->optionRepository->add($this->optionParser->parse($option));
        return $this;
    }

    public function action($action)
    {
        if (empty($this->usage)) {
            $this->usage($this->context);
        }

        $this->actions[] = $action;
        return $this;
    }

    public function run($args = null)
    {
        $optionRepository = $this->inherit ?
            $this->inherit->optionRepository->merge($this->optionRepository) : $this->optionRepository;

        $input = false;

        foreach (array_keys($this->usage) as $definition) {
            $parser = $this->usageParserBuilder->createUsageParser($definition, $optionRepository);

            if (($input = $parser->parse($args)) !== false) {
                break;
            }
        }

        if ($input === false) {
            return false;
        }

        /** @var BeforeActionEvent $event */
        $event = $this->dispatcher->dispatch(Application::EVENT_BEFORE_ACTION, new BeforeActionEvent($input, $this));

        if (!$event->isActionPrevented()) {
            foreach ($this->actions as $action) {
                call_user_func($action, $input);
            }
        }

        return true;
    }
}
