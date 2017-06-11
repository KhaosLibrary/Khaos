<?php

namespace Khaos\Bench\Tool;

use Auryn\Injector;
use Khaos\Bench\Command\CommandRouter;

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
     * @return CommandRouter|null
     */
    public function getCommandRouter();
}