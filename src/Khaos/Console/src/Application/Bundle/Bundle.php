<?php

namespace Khaos\Console\Application\Bundle;

use Khaos\Console\Application\Application;
use Khaos\Console\Application\DI\ContainerConfig;

interface Bundle extends ContainerConfig
{
    /**
     * Setup Bundle
     *
     * @param Application $application
     *
     * @return void
     */
    public function setup(Application $application);
}