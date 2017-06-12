<?php

namespace Khaos\Bench\Tool;

use Auryn\Injector;
use Khaos\Bench\Tool\ToolFunctionRouter;

interface Tool
{
    /**
     * @return ToolFunctionRouter|null
     */
    public function getToolFunctionRouter();

    /**
     * Import Resources
     *
     * @param array $resourceDefinitionData
     */
    public function import(array $resourceDefinitionData);

    /**
     * @return array
     */
    public static function resources();
}