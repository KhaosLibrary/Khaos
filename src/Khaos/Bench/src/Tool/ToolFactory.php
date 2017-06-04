<?php

namespace Khaos\Bench\Tool;

use Auryn\Injector;
use InvalidArgumentException;

class ToolFactory
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * @var Tool[]
     */
    private $tools = [];

    /**
     * @var Tool[]
     */
    private $instances = [];

    /**
     * ToolFactory constructor.
     *
     * @param Injector $injector
     */
    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    public function add(string $name, string $class)
    {
        if (!is_a($class, Tool::class, true))
            throw new InvalidArgumentException($class.' is not of type '.Tool::class);

        $this->tools[$name] = $class;
    }

    /**
     * @param string $name
     *
     * @return Tool
     */
    public function create(string $name)
    {
        if (isset($this->instances[$name]))
            return $this->instances[$name];

        if (!isset($this->tools[$name]))
            throw new InvalidArgumentException("The requested tool '{$name}' does not exist.");

        return $this->instances[$name] = $this->tools[$name]::create($this->injector);
    }
}