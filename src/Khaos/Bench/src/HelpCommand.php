<?php

namespace Khaos\Bench;

use Khaos\Bench\Command\Command;
use Khaos\Console\Usage\Input;

class HelpCommand implements Command
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