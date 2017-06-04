<?php

namespace Khaos\Bench\Tool\Docker;

use Auryn\Injector;
use Khaos\Bench\Tool\Tool;

class DockerTool implements Tool
{
    public function __construct()
    {

    }

    public static function create(Injector $injector)
    {
        return new DockerTool();
    }
}