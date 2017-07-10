<?php

namespace Khaos\Bench2\Tool\Bench;

use Exception;
use Khaos\Bench2\Bench;
use Khaos\Bench2\Expression;
use Khaos\Bench2\Tool\Bench\Resource\Command\CommandSchema;
use Khaos\Bench2\Tool\Bench\Resource\CommandNamespace\CommandNamespaceSchema;
use Khaos\Bench2\Tool\ToolPackage;
use Khaos\Schema\SchemaProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BenchPackage implements ToolPackage
{
    const NAME = 'bench';

    /**
     * @var Bench
     */
    private $bench;

    /**
     * @inheritdoc
     */
    public function setBench(Bench $bench)
    {
        $this->bench = $bench;
    }

    /**
     * @inheritdoc
     */
    public function getSchemaProvider()
    {
        $expressionHandler = $this->bench->getExpressionHandler();

        return new class ($expressionHandler) implements SchemaProvider
        {
            private $expressionHandler;

            public function __construct(Expression $expressionHandler)
            {
                $this->expressionHandler = $expressionHandler;
            }

            public function get($schema)
            {
                switch ($schema)
                {
                    case 'command':
                        return new CommandSchema($this->expressionHandler);

                    case 'namespace':
                        return new CommandNamespaceSchema($this->expressionHandler);
                }

                throw new Exception();
            }

            public function getAvailableSchemas()
            {
                return [
                    'command',
                    'namespace'
                ];
            }
        };
    }

    /**
     * @inheritdoc
     */
    public function getSubscriber()
    {
        return new class implements EventSubscriberInterface
        {
            public static function getSubscribedEvents()
            {
                return [];
            }
        };
    }

    /**
     * @inheritdoc
     */
    public function getDependencies()
    {
        return [];
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
    public function getCommandProxy()
    {
        return null;
    }
}