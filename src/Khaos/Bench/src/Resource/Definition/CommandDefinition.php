<?php

namespace Khaos\Bench\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\BaseResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;

class CommandDefinition extends BaseResourceDefinition implements ResourceDefinition
{
    const TYPE = 'bench/command';

    /**
     * @var OptionDefinitionParser
     */
    private $optionDefinitionParser = null;

    /**
     * @var OptionDefinitionRepository
     */
    private $optionsRepository = null;

    public function __construct(array $data, OptionDefinitionParser $optionDefinitionParser)
    {
        if (!isset($data['definition']['command']))
            throw new InvalidArgumentException('command is a required field in the command definition resource.');

        $data['definition']['namespace'] = $data['definition']['namespace'] ?? null;
        $data['definition']['options']   = $data['definition']['options']   ?? [];

        $this->optionDefinitionParser = $optionDefinitionParser;

        parent::__construct($data);
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getNamespace()
    {
        return $this->data['definition']['namespace'];
    }

    public function getCommand()
    {
        return $this->data['definition']['command'];
    }

    public function getOptions()
    {
        if (null == $this->optionsRepository)
        {
            $this->optionsRepository = new OptionDefinitionRepository();

            foreach ($this->data['definition']['options'] as $option)
                $this->optionsRepository->add($this->optionDefinitionParser->parse($option));
        }

        return $this->optionsRepository;
    }
}
