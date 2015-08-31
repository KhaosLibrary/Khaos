<?php

namespace Khaos\Console\Application;

use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ContextFactory
{
    private $eventDispatcher;
    private $optionDefinitionParser;
    private $defaultRoot = null;
    private $usageParserBuilder;

    /**
     * Context Factory
     *
     * @param EventDispatcher         $eventDispatcher
     * @param OptionDefinitionParser  $optionDefinitionParser
     * @param UsageParserBuilder      $usageParserBuilder
     */
    public function __construct(
        EventDispatcher $eventDispatcher,
        OptionDefinitionParser $optionDefinitionParser,
        UsageParserBuilder $usageParserBuilder
    ) {
        $this->eventDispatcher        = $eventDispatcher;
        $this->optionDefinitionParser = $optionDefinitionParser;
        $this->usageParserBuilder     = $usageParserBuilder;
    }

    public function setDefaultRoot(Context $root)
    {
        $this->defaultRoot = $root;
    }

    /**
     * Create Context
     *
     * @param string   $name
     * @param Context  $root
     *
     * @return Context
     */
    public function create($name, Context $root = null)
    {
        return new Context(
            $name,
            $this->eventDispatcher,
            $root?:$this->defaultRoot,
            $this->optionDefinitionParser,
            $this->usageParserBuilder
        );
    }
}
