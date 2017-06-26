<?php

namespace Khaos\Bench;

use Symfony\Component\EventDispatcher\Event;

class BenchRunEvent extends Event
{
    const NAME = 'bench.run';

    /**
     * @var Bench
     */
    private $bench;

    /**
     * @var
     */
    private $args;

    /**
     * BenchRunEvent constructor.
     *
     * @param Bench $bench
     * @param array $args
     */
    public function __construct(Bench $bench, $args)
    {
        $this->bench = $bench;
        $this->args  = $args;
    }

    /**
     * @return Bench
     */
    public function getBench(): Bench
    {
        return $this->bench;
    }

    /**
     * @return mixed
     */
    public function getArgs()
    {
        return $this->args;
    }
}