<?php

namespace Khaos\Bench2\Tool\Console;

use Khaos\Bench2\BenchApplication;
use Khaos\Bench2\Tool\ToolPackage;

class ConsolePackage implements ToolPackage
{
    const NAME = 'console';

    public function getSchemaProvider(BenchApplication $bench)
    {
        return null;
    }

    public function getSubscriber(BenchApplication $bench)
    {
        return null;
    }

    public function getTool(BenchApplication $bench)
    {
        return new Console();
    }

    public function getDependencies()
    {
        return null;
    }

    public function getName()
    {
        return self::NAME;
    }

}