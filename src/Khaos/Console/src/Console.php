<?php

namespace Khaos\Console;

use Khaos\Console\Usage\Model\OptionDefinitionRepository;
use Khaos\Console\Usage\Parser\UsageParserBuilder;

class Console
{
    public static function application($name, $version = '1.0')
    {

    }

    public static function usage($definition, $cmd = null, OptionDefinitionRepository $optionRepository = null)
    {
        $cmd         = empty($cmd) ? [] : preg_split('/\s+/', $cmd);
        $usageParser = (new UsageParserBuilder())->createUsageParser($definition, $optionRepository);

        return $usageParser->parse($cmd);
    }
}
