<?php

namespace Khaos\Bench\Tool\Bench\Functions;

use Khaos\Bench\Bench;
use Khaos\Bench\Tool\ToolFunction;

class VersionFunction implements ToolFunction
{
    public function __invoke()
    {
        echo Bench::VERSION;
    }
}