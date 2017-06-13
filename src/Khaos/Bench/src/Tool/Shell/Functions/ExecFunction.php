<?php

namespace Khaos\Bench\Tool\Shell\Functions;

use Khaos\Bench\Tool\ToolFunction;

class ExecFunction implements ToolFunction
{
    public function __invoke($cmd)
    {
        system($cmd);
    }
}