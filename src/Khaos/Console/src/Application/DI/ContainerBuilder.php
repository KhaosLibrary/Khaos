<?php

namespace Khaos\Console\Application\DI;

use Aura\Di\Container;
use Aura\Di\Factory;

class ContainerBuilder
{
    /**
     *
     * Creates a new DI container, adds pre-existing service objects, applies
     * Config classes to define() services, locks the container, and applies
     * the Config instances to modify() services.
     *
     * @param array $services Pre-existing service objects to set into the
     * container.
     *
     * @param array $configClasses A list of Config classes to instantiate and
     * invoke for configuring the container.
     *
     * @param bool $autoResolve Enable or disable auto-resolve after the
     * define() step?
     *
     * @return Container
     *
     */
    public function newInstance(array $services = [], array $configClasses = [], $autoResolve = true)
    {
        $di = new Container(new Factory);
        $di->setAutoResolve($autoResolve);

        foreach ($services as $key => $val) {
            $di->set($key, $val);
        }

        $configs = [];

        foreach ($configClasses as $class) {
            $configs[] = $config = (is_string($class)) ? $di->newInstance($class) : $class;
            $config->define($di);
        }

        $di->lock();

        foreach ($configs as $config) {
            $config->modify($di);
        }

        return $di;
    }
}