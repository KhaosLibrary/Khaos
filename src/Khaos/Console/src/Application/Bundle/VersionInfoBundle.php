<?php

namespace Khaos\Console\Application\Bundle;

use Aura\Di\Container;
use Khaos\Console\Application\Application;

class VersionInfoBundle implements Bundle
{
    /**
     * @inheritDoc
     */
    public function setup(Application $application)
    {
        return;
    }

    /**
     * @inheritDoc
     */
    public function define(Container $di)
    {
        return;
    }

    /**
     * @inheritDoc
     */
    public function modify(Container $di)
    {
        return;
    }
}
