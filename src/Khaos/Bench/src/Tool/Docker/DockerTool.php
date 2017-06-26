<?php

namespace Khaos\Bench\Tool\Docker;

use Exception;
use Khaos\Bench\Bench;
use Khaos\Bench\CacheDefinitionsEvent;
use Khaos\Bench\PrepareExpressionHandlerEvent;
use Khaos\Bench\PrepareToolsEvent;
use Khaos\Bench\Tool\Docker\Resource\Image\DockerImageSchema;
use Khaos\Bench\Tool\Docker\Operation\Build;
use Khaos\Bench\Tool\Tool;
use Khaos\Bench\Tool\Operation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DockerTool
 *
 * @package Khaos\Bench\Tool\Docker
 */
class DockerTool implements Tool
{
    const NAME = 'docker';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param CacheDefinitionsEvent $event
     */
    public function onCacheDefinitions(CacheDefinitionsEvent $event)
    {
        $bench       = $event->getBench();
        $definitions = $event->getDefinitionRepository();

        if ($definitions->count('docker/image') == 0)
            return;

        $bench->import(__DIR__.'/_config/definition/namespace/docker.yml');
        $bench->import(__DIR__.'/_config/definition/command/build.yml');
        $bench->import(__DIR__.'/_config/definition/command/push.yml');
    }

    /**
     * @param PrepareToolsEvent $event
     */
    public function onPrepareTools(PrepareToolsEvent $event)
    {
        $definitions = $event->getDefinitionRepository();

        if ($definitions->count('docker/image') == 0)
            return;

        $schemas = $definitions->getSchemaRepository();
        $types   = $definitions->getTypeRepository();

        $schemas->add(new DockerImageSchema($types));
    }

    /**
     * @param PrepareExpressionHandlerEvent $event
     */
    public function onPrepareExpressionHandler(PrepareExpressionHandlerEvent $event)
    {
        $event->getExpressionHandler()->addGlobalValue('docker', new DockerToolOperationProxy());
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            CacheDefinitionsEvent::NAME          => 'onCacheDefinitions',
            PrepareToolsEvent::NAME              => 'onPrepareTools',
            PrepareExpressionHandlerEvent::NAME  => 'onPrepareExpressionHandler'
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
        // TODO: Implement getOperationProxy() method.
    }
}