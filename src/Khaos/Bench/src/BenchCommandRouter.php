<?php

namespace Khaos\Bench;

use Auryn\Injector;
use Khaos\Bench\Command\CommandRouter;
use Khaos\Console\Usage\Input;

/**
 * Class BenchCommandRouter
 *
 * @package Khaos\Bench
 *
 * @method void help(Input $input)
 * @method void version()
 */
class BenchCommandRouter implements CommandRouter
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * @var array
     */
    private $commandMap =
    [
        'help'    => HelpCommand::class,
        'version' => VersionCommand::class
    ];

    /**
     * BenchCommandRouter constructor.
     *
     * @param Injector $injector
     */
    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @param string  $name
     * @param array   $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array($this->injector->make($this->commandMap[$name]), $arguments);
    }
}