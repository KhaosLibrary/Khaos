<?php

namespace Khaos\Bench\Tool\Bench\Command;


use Khaos\Bench\Bench;
use Khaos\Bench\Command\Event\InvalidUsageEvent;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class ShowHelpOnInvalidUsageEvent
{
    /**
     * @var Bench
     */
    private $bench;

    /**
     * @var ConsoleOutputInterface
     */
    private $output;

    /**
     * ShowHelpOnInvalidUsageEvent constructor.
     *
     * @param Bench                   $bench
     * @param ConsoleOutputInterface  $output
     */
    public function __construct(Bench $bench, ConsoleOutputInterface $output)
    {
        $this->bench  = $bench;
        $this->output = $output;
    }

    public function __invoke(InvalidUsageEvent $event)
    {
        $this->output->writeln('<error>Invalid Usage</error>');
    }
}