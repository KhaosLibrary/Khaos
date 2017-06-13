<?php

namespace Khaos\Bench\Tool\Bench\Functions;

use Khaos\Bench\Bench;
use Khaos\Bench\Tool\ToolFunction;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class VersionFunction implements ToolFunction
{
    /**
     * @var ConsoleOutputInterface
     */
    private $output;

    public function __construct(ConsoleOutputInterface $output)
    {
        $this->output = $output;
    }

    public function __invoke()
    {
        $this->output->writeln('<green>Bench</green> version <yellow>'.Bench::VERSION.'</yellow> build <yellow>8ce371cdc1f0005e087e9ca5c265b52b5f560fd4</yellow>');
    }
}