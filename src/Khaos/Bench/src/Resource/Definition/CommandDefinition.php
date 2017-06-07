<?php

namespace Khaos\Bench\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\Definition\BaseResourceDefinition;
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
        $this->validateDefinition($data);

        $data['metadata']['id'] = $data['metadata']['id'] ?? self::getUniqueId();

        $data['definition']['namespace'] = $data['definition']['namespace'] ?? null;
        $data['definition']['options']   = $data['definition']['options']   ?? [];
        $data['definition']['usage']     = $this->generateUsagePattern($data);

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

    public function getUsage()
    {
        return $this->data['definition']['usage'];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function generateUsagePattern(array $data)
    {
        if (!isset($data['definition']['usage']))
        {
            $usage = 'bench ';

            if ($data['definition']['namespace'] !== null)
                $usage .= $data['definition']['namespace'] . ' ';

            $usage .= $data['definition']['command'] . ' ';
            $usage .= '[options]';

            $data['definition']['usage'] = $usage;
        }

        if (strpos($data['definition']['usage'], '[options]') === false)
            $data['definition']['usage'] .= ' [options]';

        return $data['definition']['usage'];
    }

    /**
     * @param array $data
     */
    private function validateDefinition(array $data)
    {
        if (!isset($data['definition']['command']))
            throw new InvalidArgumentException('command is a required field in the command definition resource.');

        if (str_word_count($data['definition']['command']) > 1)
            throw new InvalidArgumentException('command must be a single word, override the usage pattern for more complexity.');
    }

    public static function getUniqueId()
    {
        static $count = 0;
        return '_internal/bench/command/'.$count++;
    }

    public function getRun()
    {
        return $this->data['definition']['run'];
    }
}
