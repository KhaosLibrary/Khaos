<?php

namespace Khaos\Bench\Tool\Twig;

use Khaos\Bench\Bench;
use Khaos\Bench\PrepareExpressionHandlerEvent;
use Khaos\Bench\Tool\Tool;

class TwigTool implements Tool
{
    const NAME = 'twig';

    /**
     * @var Bench
     */
    private $bench;

    /**
     * TwigTool constructor.
     *
     * @param Bench $bench
     */
    public function __construct(Bench $bench)
    {
        $this->bench = $bench;
    }

    /**
     * @param PrepareExpressionHandlerEvent $event
     */
    public function onPrepareExpressionHandler(PrepareExpressionHandlerEvent $event)
    {
        $event->getExpressionHandler()->addGlobalValue('twig', new TwigToolOperationProxy($this->bench));
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
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @inheritdoc
     */
    public static function create(Bench $bench)
    {
        return new self($bench);
    }

    /**
     * @return mixed
     */
    public function getOperationProxy()
    {
        // TODO: Implement getOperationProxy() method.
    }
}