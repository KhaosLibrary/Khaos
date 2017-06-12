<?php

namespace Khaos\Bench\Tool\Bench;

use Auryn\Injector;
use Khaos\Bench\Tool\Bench\BenchFunctionRouter;
use Khaos\Bench\Tool\ToolFunctionRouter;
use Khaos\Bench\Tool\Tool;

class BenchTool implements Tool
{
    /**
     * @var BenchFunctionRouter
     */
    private $commandRouter;

    /**
     * BenchTool constructor.
     *
     * @param BenchFunctionRouter $commandRouter
     */
    public function __construct(BenchFunctionRouter $commandRouter)
    {
        $this->commandRouter = $commandRouter;
    }

    /**
     * @return ToolFunctionRouter|null
     */
    public function getToolFunctionRouter()
    {
        return $this->commandRouter;
    }

    /**
     * Create new instance bench tool
     *
     * @param Injector $injector
     *
     * @return Tool
     */
    public static function create(Injector $injector)
    {
        return new self(new BenchFunctionRouter($injector));
    }

    /**
     * @return array
     */
    public static function resources()
    {
        return [
            'bench',
            'bench/import',
            'bench/command',
            'bench/namespace'
        ];
    }
}