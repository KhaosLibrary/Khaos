<?php

namespace Khaos\Bench;

use Khaos\Bench\Command\Command;

class VersionCommand implements Command
{
    public function __invoke()
    {
        echo Bench::VERSION;
    }
}