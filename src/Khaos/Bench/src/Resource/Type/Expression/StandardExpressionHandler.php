<?php


namespace Khaos\Bench\Resource\Type\Expression;

use Khaos\Bench\PrepareExpressionHandlerEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class StandardExpressionHandler extends ExpressionLanguage implements ExpressionHandler
{
    /**
     * @var array
     */
    private $globalValues = [];

    /**
     * StandardExpressionHandler constructor.
     *
     * @param EventDispatcher $eventDispatcher
     * @param null            $cache
     * @param array           $providers
     */
    public function __construct(EventDispatcher $eventDispatcher, $cache = null, $providers = array())
    {
        parent::__construct($cache, $providers);
        $eventDispatcher->dispatch(PrepareExpressionHandlerEvent::NAME, new PrepareExpressionHandlerEvent($this));
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function addGlobalValue($name, $value)
    {
        $this->globalValues[$name] = $value;
    }

    /**
     * @return array
     */
    public function getGlobalValues()
    {
        return $this->globalValues;
    }
}