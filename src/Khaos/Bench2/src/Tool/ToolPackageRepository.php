<?php

namespace Khaos\Bench2\Tool;

use Exception;

class ToolPackageRepository
{
    /**
     * @var ToolPackage
     */
    private $packages = [];

    /**
     * ToolPackageCollection constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param ToolPackage $package
     */
    public function add(ToolPackage $package)
    {
        $this->packages[$package->getName()] = $package;
    }

    /**
     * @param string $toolPackage
     *
     * @return ToolPackage
     *
     * @throws Exception
     */
    public function get($toolPackage)
    {
        if (!isset($this->packages[$toolPackage]))
            throw new Exception();

        return $this->packages[$toolPackage];
    }
}