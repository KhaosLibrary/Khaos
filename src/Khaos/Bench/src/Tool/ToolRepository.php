<?php

namespace Khaos\Bench\Tool;

interface ToolRepository
{
    /**
     * Add Tool
     *
     * @param Tool $tool
     *
     * @return void
     */
    public function add(Tool $tool);

    /**
     * Get Tool
     *
     * @param string $tool
     *
     * @return Tool
     */
    public function __get($tool);
}