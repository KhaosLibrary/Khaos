<?php

namespace Khaos\Bench\Tool\Bench\Functions;

use Khaos\Bench\Tool\ToolFunction;
use Khaos\Bench\Tool\Bench\Functions\Help\ContextualHelpBuilder;
use Khaos\Console\Usage\Input;

class HelpFunction implements ToolFunction
{
    /**
     * @var ContextualHelpBuilder
     */
    private $helpBuilder;

    /**
     * HelpCommand constructor.
     *
     * @param ContextualHelpBuilder $helpBuilder
     */
    public function __construct(ContextualHelpBuilder $helpBuilder)
    {
        $this->helpBuilder = $helpBuilder;
    }

    /**
     * @param Input $input
     */
    public function __invoke(Input $input)
    {
        $this->helpBuilder->build($input);
    }
}