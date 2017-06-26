<?php

namespace Khaos\Bench\Tool;

use Exception;

class StandardToolRepository implements ToolRepository
{
    /**
     * @var Tool[]
     */
    private $tools = [];

    /**
     * Add Tool
     *
     * @param Tool $tool
     *
     * @return void
     */
    public function add(Tool $tool)
    {
        $this->tools[$tool->getName()] = $tool;
    }

    /**
     * Get Tool
     *
     * @param string $tool
     *
     * @return Tool
     * @throws Exception
     */
    public function __get($tool)
    {
        if (!isset($this->tools[$tool])) {
            throw new Exception("Tool '{$tool}' is not loaded.");
        }

        return $this->{$tool} = $this->tools[$tool];
    }
}