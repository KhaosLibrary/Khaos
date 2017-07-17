<?php

namespace Khaos\Bench2\Tool\Bench;

use Exception;
use Khaos\Bench2\BenchApplication;
use Khaos\Bench2\Events\WorkspaceResourcesLoadedEvent;
use Khaos\Bench2\Expression;
use Khaos\Bench2\Tool\Bench\Resource\Command\CommandSchema;
use Khaos\Bench2\Tool\Bench\Resource\CommandNamespace\CommandNamespaceSchema;
use Khaos\Bench2\Tool\Bench\Resource\Event\EventSchema;
use Khaos\Bench2\Tool\Bench\Resource\Listener\ListenerSchema;
use Khaos\Bench2\Tool\ToolPackage;
use Khaos\Console\Usage\Input;
use Khaos\Schema\FileDataProvider;
use Khaos\Schema\SchemaProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Khaos\Bench2\Tool\Bench\Bench as BenchTool;

class BenchPackage implements ToolPackage
{
    const NAME = 'bench';

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
    public function getDependencies()
    {
        return [
            'console',
            'twig'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSchemaProvider(BenchApplication $bench)
    {
        $expressionHandler = $bench->getExpressionHandler();

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

                    case 'event':
                        return new EventSchema($this->expressionHandler);

                    case 'listener':
                        return new ListenerSchema($this->expressionHandler);
                }

                throw new Exception();
            }

            public function getAvailableSchemas()
            {
                return [
                    'command',
                    'namespace',
                    'event',
                    'listener'
                ];
            }
        };
    }

    /**
     * @inheritdoc
     */
    public function getSubscriber(BenchApplication $bench)
    {
        return new class implements EventSubscriberInterface
        {
            public function onWorkspaceResourcesLoaded(WorkspaceResourcesLoadedEvent $event)
            {
                $resources = $event->getResources();
                $resources->addDataProvider(new FileDataProvider(__DIR__ . '/_assets/common.yaml'));
            }

            public static function getSubscribedEvents()
            {
                return [
                    WorkspaceResourcesLoadedEvent::NAME => 'onWorkspaceResourcesLoaded'
                ];
            }
        };
    }

    /**
     * @inheritdoc
     */
    public function getTool(BenchApplication $bench)
    {
        return new class($bench) extends BenchTool
        {
            private $bench;

            public function __construct(BenchApplication $bench)
            {
                $this->bench = $bench;
            }

            public function dispatch($event)
            {
                return $this->tool->dispatch($event);
            }

            public function help(Input $input)
            {
                return $this->tool->help($input);
            }

            public function version()
            {
                return $this->tool->version();
            }

            public function __get($key)
            {
                return $this->tool = new BenchTool($this->bench, $this->bench->tool('console'), $this->bench->tool('twig'));
            }
        };
    }
}