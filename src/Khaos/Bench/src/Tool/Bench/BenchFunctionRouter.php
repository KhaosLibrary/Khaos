<?php

namespace Khaos\Bench\Tool\Bench;

use Auryn\Injector;
use Khaos\Bench\Tool\ToolFunctionRouter;
use Khaos\Bench\Tool\Bench\Functions\HelpFunction;
use Khaos\Bench\Tool\Bench\Functions\VersionFunction;
use Khaos\Console\Usage\Input;

/**
 * Class BenchCommandRouter
 *
 * @package Khaos\Bench
 *
 * @method void help(Input $input)
 * @method void version()
 */
class BenchFunctionRouter implements ToolFunctionRouter
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
        'help'    => HelpFunction::class,
        'version' => VersionFunction::class
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