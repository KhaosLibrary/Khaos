<?php

namespace Khaos\Bench\Tool;

use Auryn\Injector;

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
}