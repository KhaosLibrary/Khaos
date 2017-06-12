<?php

namespace Khaos\Bench\Tool\Bench\Resource\DefinitionFactory;

use Khaos\Bench\Tool\Bench\Resource\Definition\CommandDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;

class CommandDefinitionFactory implements ResourceDefinitionFactory
{
    /**
     * @var OptionDefinitionParser
     */
    private $optionDefinitionParser;

    /**
     * CommandDefinitionFactory constructor.
     *
     * @param OptionDefinitionParser $optionDefinitionParser
     */
    public function __construct(OptionDefinitionParser $optionDefinitionParser)
    {
        $this->optionDefinitionParser = $optionDefinitionParser;
    }

    public function getType()
    {
        return CommandDefinition::TYPE;
    }

    /**
     * Create Resource Definition From Array
     *
     * @param array $definition
     *
     * @return ResourceDefinition
     */
    public function create(array $definition)
    {
        return new CommandDefinition($definition, $this->optionDefinitionParser);
    }
}
