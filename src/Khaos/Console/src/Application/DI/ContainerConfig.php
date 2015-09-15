<?php

namespace Khaos\Console\Application\DI;

use Aura\Di\Container;

interface ContainerConfig
{
    /**
     * Define params, setters, and services before the container is locked.
     *
     * @param Container $di The DI container.
     *
     * @return null
     */
    public function define(Container $di);

    /**
     * Modify service objects after the container is locked.
     *
     * @param Container $di The DI container.
     *
     * @return null
     */
    public function modify(Container $di);
}