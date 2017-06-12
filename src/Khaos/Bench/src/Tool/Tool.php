<?php

namespace Khaos\Bench\Tool;

use Auryn\Injector;
use Khaos\Bench\Tool\ToolFunctionRouter;

interface Tool
{
    /**
     * Create new instance bench tool
     *
     * @param Injector $injector
     *
     * @return Tool
     */
    public static function create(Injector $injector);

    /**
     * @return ToolFunctionRouter|null
     */
    public function getToolFunctionRouter();

    /**
     * @return array
     */
    public static function resources();
}