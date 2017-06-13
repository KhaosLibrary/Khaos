<?php

namespace Khaos\Bench\Tool\Shell;

use Auryn\Injector;
use Khaos\Bench\Tool\Shell\Functions\ExecFunction;
use Khaos\Bench\Tool\ToolFunctionRouter;

/**
 * Class ShellFunctionRouter
 *
 * @package Khaos\Bench

 * @method void exec(string $cmd)
 */
class ShellFunctionRouter implements ToolFunctionRouter
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
        'exec'    => ExecFunction::class
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

    /**
     * @param $cmd
     */
    public function __invoke($cmd)
    {
        $this->exec($cmd);
    }
}