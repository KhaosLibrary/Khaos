<?php

namespace Khaos\Bench\Tool;

use Khaos\Bench\Bench;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface Tool extends EventSubscriberInterface
{
    /**
     * Get Tool Name
     *
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getOperationProxy();

    /**
     * Create Command of Tool
     *
     * @param Bench $bench
     *
     * @return Tool
     */
    public static function create(Bench $bench);
}