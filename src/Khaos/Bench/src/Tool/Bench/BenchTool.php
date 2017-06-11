<?php

namespace Khaos\Bench\Tool\Bench;

use Auryn\Injector;
use Khaos\Bench\BenchCommandRouter;
use Khaos\Bench\Command\CommandRouter;
use Khaos\Bench\Tool\Tool;

class BenchTool implements Tool
{
    /**
     * @var BenchCommandRouter
     */
    private $commandRouter;

    /**
     * BenchTool constructor.
     *
     * @param BenchCommandRouter $commandRouter
     */
    public function __construct(BenchCommandRouter $commandRouter)
    {
        $this->commandRouter = $commandRouter;
    }

    /**
     * @return CommandRouter|null
     */
    public function getCommandRouter()
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
        return new self(new BenchCommandRouter($injector));
    }
}