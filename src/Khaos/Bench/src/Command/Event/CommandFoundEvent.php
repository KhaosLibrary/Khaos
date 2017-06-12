<?php

namespace Khaos\Bench\Command\Event;

use Khaos\Console\Usage\Input;
use Symfony\Component\EventDispatcher\Event;

class CommandFoundEvent extends Event
{
    const NAME = 'command.runner.parsed';

    /**
     * @var Input
     */
    private $input;

    public function __construct(Input $input)
    {
        $this->input = $input;
    }

    public function getInput()
    {
        return $this->input;
    }
}