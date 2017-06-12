<?php

namespace Khaos\Bench\Tool\Docker;

use Auryn\Injector;
use Khaos\Bench\Tool\ToolFunctionRouter;
use Khaos\Bench\Tool\Tool;

class DockerTool implements Tool
{
    public function __construct()
    {

    }

    public static function create(Injector $injector)
    {
        return new self();
    }

    /**
     * @return ToolFunctionRouter|null
     */
    public function getToolFunctionRouter()
    {
        return null;
    }

    /**
     * @return array
     */
    public static function resources()
    {
        return [
            'docker/registry',
            'docker/image'
        ];
    }
}