<?php

namespace Khaos\Bench\Tool\Bench;

use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Bench\Operation\Help\ContextualHelpBuilder;
use Khaos\Console\Usage\Input;

class BenchToolOperationProxy
{
    /**
     * @var Bench
     */
    private $bench;

    /**
     * BenchToolOperationProxy constructor.
     *
     * @param Bench $bench
     */
    public function __construct(Bench $bench)
    {
        $this->bench = $bench;
    }

    /**
     * @param Input $input
     */
    public function help(Input $input)
    {
        (new ContextualHelpBuilder($this->bench))->build($input);
    }

    /**
     *
     */
    public function version()
    {
        echo '1.0';
    }
}