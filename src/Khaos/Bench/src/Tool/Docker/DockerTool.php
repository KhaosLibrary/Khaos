<?php

namespace Khaos\Bench\Tool\Docker;

use Khaos\Bench\Tool\ToolFunctionRouter;
use Khaos\Bench\Tool\Tool;

class DockerTool implements Tool
{
    /**
     * DockerTool constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return ToolFunctionRouter|null
     */
    public function getToolFunctionRouter()
    {
        return null;
    }

    /**
     * Import Resources
     *
     * @param array $resourceDefinitionData
     */
    public function import(array $resourceDefinitionData)
    {

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

    /**
     * @return string|null
     */
    public function getManifest()
    {
        return null;
    }
}