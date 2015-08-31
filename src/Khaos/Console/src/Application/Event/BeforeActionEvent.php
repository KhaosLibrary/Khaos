<?php

namespace Khaos\Console\Application\Event;

use Khaos\Console\Application\Context;
use Khaos\Console\Usage\Input;
use Symfony\Component\EventDispatcher\Event;

class BeforeActionEvent extends Event
{
    /**
     * @var Input
     */
    private $input;

    private $preventAction = false;
    /**
     * @var Context
     */
    private $context;

    public function __construct(Input $input, Context $context)
    {
        $this->input   = $input;
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function isActionPrevented()
    {
        return $this->preventAction;
    }

    public function preventAction()
    {
        $this->preventAction = true;
    }
}
