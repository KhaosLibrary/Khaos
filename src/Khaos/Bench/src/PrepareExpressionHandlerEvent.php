<?php

namespace Khaos\Bench;

use Khaos\Bench\Resource\Type\Expression\ExpressionHandler;
use Symfony\Component\EventDispatcher\Event;

class PrepareExpressionHandlerEvent extends Event
{
    const NAME = 'bench.prepare_expression_handler';

    /**
     * @var ExpressionHandler
     */
    private $expressionHandler;

    /**
     * PrepareExpressionHandler constructor.
     *
     * @param ExpressionHandler $expressionHandler
     */
    public function __construct(ExpressionHandler $expressionHandler)
    {
        $this->expressionHandler = $expressionHandler;
    }

    /**
     * @return ExpressionHandler
     */
    public function getExpressionHandler(): ExpressionHandler
    {
        return $this->expressionHandler;
    }
}