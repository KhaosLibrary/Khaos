<?php

use Khaos\Console\Console;
use Khaos\Console\Usage\Model\OptionDefinitionRepository;

/**
 * Usage
 *
 * @param string $definition
 * @param string $cmd
 * @param OptionDefinitionRepository $optionRepository
 *
 * @return false|string[]
 * @throws Exception
 */
function usage($definition, $cmd = null, OptionDefinitionRepository $optionRepository = null)
{
    $result = Console::usage($definition, $cmd, $optionRepository);

    if (is_bool($result))
        return $result;

    if (count($result) == 0)
        return [];

    return explode("\n", (string)$result);
}