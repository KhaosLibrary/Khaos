<?php

namespace Khaos\Bench\Tool\Docker;

use Khaos\Bench\Bench;
use Khaos\Bench\CacheDefinitionsEvent;
use Khaos\Bench\PrepareExpressionHandlerEvent;
use Khaos\Bench\PrepareToolsEvent;
use Khaos\Bench\Tool\Docker\Resource\Image\DockerImageSchema;
use Khaos\Bench\Tool\Docker\Resource\Registry\DockerRegistrySchema;
use Khaos\Bench\Tool\Tool;

/**
 * Class DockerTool
 *
 * @package Khaos\Bench\Tool\Docker
 */
class DockerTool implements Tool
{
    const NAME = 'docker';
    /**
     * @var Bench
     */
    private $bench;

    /**
     * @var DockerToolOperationProxy
     */
    private $operationProxy;

    /**
     * DockerTool constructor.
     *
     * @param Bench $bench
     */
    public function __construct(Bench $bench)
    {
        $this->bench          = $bench;
        $this->operationProxy = new DockerToolOperationProxy($bench);
    }

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
        $enabled     = false;

        if ($definitions->count('docker/image') > 0) {
            $bench->import(__DIR__.'/_config/definition/command/build.yml');
            $enabled = true;
        }

        if ($definitions->count('docker/registry') > 0) {
            $bench->import(__DIR__ . '/_config/definition/command/push.yml');
            $enabled = true;
        }

        if (file_exists(BENCH_WORKING_DIRECTORY.'/docker-compose.yml')) {
            $bench->import(__DIR__ . '/_config/definition/command/stop.yml');
            $bench->import(__DIR__ . '/_config/definition/command/start.yml');
            $bench->import(__DIR__ . '/_config/definition/command/destroy.yml');
        }

        if ($enabled) {
            $bench->import(__DIR__ . '/_config/definition/namespace/docker.yml');
        }
    }

    /**
     * @param PrepareToolsEvent $event
     */
    public function onPrepareTools(PrepareToolsEvent $event)
    {
        $definitions = $event->getDefinitionRepository();
        $schemas     = $definitions->getSchemaRepository();
        $types       = $definitions->getTypeRepository();

        if ($definitions->count('docker/image') > 0) {
            $schemas->add(new DockerImageSchema($types));
        }

        if ($definitions->count('docker/registry') > 0) {
            $schemas->add(new DockerRegistrySchema($types));
        }
    }

    /**
     * @param PrepareExpressionHandlerEvent $event
     */
    public function onPrepareExpressionHandler(PrepareExpressionHandlerEvent $event)
    {
        $event->getExpressionHandler()->addGlobalValue('docker', $this->operationProxy);
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
        return new self($bench);
    }

    /**
     * @return mixed
     */
    public function getOperationProxy()
    {
        return $this->operationProxy;
    }
}