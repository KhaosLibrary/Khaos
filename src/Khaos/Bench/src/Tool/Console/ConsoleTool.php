<?php

namespace Khaos\Bench\Tool\Console;

use Exception;
use Khaos\Bench\Bench;
use Khaos\Bench\PrepareExpressionHandlerEvent;
use Khaos\Bench\PrepareToolsEvent;
use Khaos\Bench\Tool\Console\Operation\Write;
use Khaos\Bench\Tool\Operation;
use Khaos\Bench\Tool\Tool;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConsoleTool implements Tool
{
    const NAME = 'console';

    /**
     * @var ConsoleToolOperationProxy
     */
    private $operationProxy;

    /**
     * ConsoleTool constructor.
     */
    public function __construct()
    {
        $this->operationProxy = new ConsoleToolOperationProxy();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param PrepareExpressionHandlerEvent $event
     */
    public function onPrepareExpressionHandler(PrepareExpressionHandlerEvent $event)
    {
        $event->getExpressionHandler()->addGlobalValue('console', new ConsoleToolOperationProxy());
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            PrepareExpressionHandlerEvent::NAME => 'onPrepareExpressionHandler'
        ];
    }

    /**
     * Create Instance of Tool
     *
     * @param Bench $bench
     *
     * @return Tool
     */
    public static function create(Bench $bench)
    {
        return new self();
    }

    /**
     * @return mixed
     */
    public function getOperationProxy()
    {
        return $this->operationProxy;
    }
}