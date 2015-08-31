<?php

namespace Khaos\Console\Application\Plugin;

use Khaos\Console\Application\Application;

interface Plugin
{
    public function setup(Application $application);
}
