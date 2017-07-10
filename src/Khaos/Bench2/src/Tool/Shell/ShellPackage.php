<?php

namespace Khaos\Bench2\Tool\Shell;

use Khaos\Bench2\Tools\ToolPackage;

class ShellPackage implements ToolPackage
{
    const NAME = 'shell';

    /**
     * ShellPackage constructor.
     */
    public function __construct()
    {

    }

    /**
     * @inheritdoc
     */
    public function setup()
    {

    }

    /**
     * @inheritdoc
     */
    public function getToolName()
    {
        return self::NAME;
    }
}