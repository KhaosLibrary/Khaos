<?php

namespace Khaos\Bench2\Tool\Shell;

use Khaos\Bench2\Tool\Console\Console;
use Khaos\Bench2\Tools\Tool;

class Shell implements Tool
{
    /**
     * @var Console
     */
    private $console;

    /**
     * Shell constructor.
     *
     * @param Console $console
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    public function exec($command)
    {

    }
}

