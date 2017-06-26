<?php

namespace Khaos\Bench;

use Exception;
use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Definition\DefinitionRepository;
use Khaos\Bench\Resource\Definition\DefinitionRepositoryFactory;
use Khaos\Bench\Resource\Loader\Loader;
use Khaos\Bench\Resource\Type\Expression\ExpressionHandler;
use Khaos\Bench\Tool\ToolRepository;
use Khaos\Cache\CacheItem;
use Khaos\Cache\CacheItemPool;
use Khaos\Cache\FileCacheItemPool;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Bench
{

    /**
     * Working directory of the bench.
     *
     * @var string
     */
    private $workingDirectory;

    /**
     * @var FileCacheItemPool
     */
    private $cachePool;

    /**
     * Repository holding all definitions after file load.
     *
     * @var DefinitionRepository
     */
    private $definitionRepository;

    /**
     * @var Loader
     */
    private $definitionLoader;

    /**
     * @var DefinitionRepositoryFactory
     */
    private $definitionRepositoryFactory;

    /**
     * Definition Cache
     *
     * @var CacheItem
     */
    private $definitionCache;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var ToolRepository
     */
    private $toolRepository;
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ExpressionHandler
     */
    private $expressionHandler;

    /**
     * Bench constructor.
     *
     * @param string $workingDirectory
     * @param CacheItemPool $cachePool
     * @param Loader $definitionLoader
     * @param DefinitionRepositoryFactory $definitionRepositoryFactory
     * @param EventDispatcher $eventDispatcher
     * @param ToolRepository $toolRepository
     * @param Registry $registry
     * @param ExpressionHandler $expressionHandler
     */
    public function __construct($workingDirectory, CacheItemPool $cachePool, Loader $definitionLoader, DefinitionRepositoryFactory $definitionRepositoryFactory, EventDispatcher $eventDispatcher, ToolRepository $toolRepository, Registry $registry, ExpressionHandler $expressionHandler)
    {
        $this->workingDirectory            = $workingDirectory;
        $this->definitionLoader            = $definitionLoader;
        $this->definitionRepositoryFactory = $definitionRepositoryFactory;
        $this->cachePool                   = $cachePool;
        $this->definitionCache             = $this->cachePool->get('definitions', []);
        $this->definitionRepository        = $this->definitionRepositoryFactory->create($this->definitionCache->value());
        $this->eventDispatcher             = $eventDispatcher;
        $this->toolRepository              = $toolRepository;
        $this->registry                    = $registry;
        $this->expressionHandler           = $expressionHandler;
    }

    /**
     * Import definitions from file
     *
     * @param string $file
     */
    public function import($file)
    {
        if ($this->definitionCache->isHit())
            return;

        if (!file_exists($file))
            $file = $this->workingDirectory.'/'.$file;

        foreach ($this->definitionLoader->load($file) as $definitionData)
            $this->definitionRepository->add($definitionData);
    }

    public function tool($tool)
    {
        return $this->toolRepository->{$tool}->getOperationProxy();
    }

    public function run($args)
    {
        // Prepare Bench Tools

        $this->eventDispatcher->dispatch(PrepareToolsEvent::NAME, new PrepareToolsEvent($this, $this->definitionRepository));

        // If required add command definitions

        if ($this->definitionCache->isHit() === false)
        {
            $this->eventDispatcher->dispatch(
                CacheDefinitionsEvent::NAME,
                new CacheDefinitionsEvent($this, $this->definitionRepository)
            );

            $this->definitionCache->set($this->definitionRepository->export());
        }

        // Run Command

        $this->eventDispatcher->dispatch(BenchRunEvent::NAME, new BenchRunEvent($this, $this->prepareArguments($args)));
    }

    /**
     * @return DefinitionRepository
     */
    public function getDefinitionRepository(): DefinitionRepository
    {
        return $this->definitionRepository;
    }

    /**
     * @return CacheItemPool
     */
    public function getCachePool(): CacheItemPool
    {
        return $this->cachePool;
    }

    private function prepareArguments($args)
    {
        $args[0] = 'bench';
        return $args;
    }

    public static function getWorkingDirectory($search, $file = 'bench.yml')
    {
        $search = $search.DIRECTORY_SEPARATOR;
        $length = strlen($search) + 1;
        $offset = $length;

        while (($offset = strrpos($search, DIRECTORY_SEPARATOR, $offset - $length)) !== false)
        {
            if (file_exists($candidate = substr($search, 0, $offset).DIRECTORY_SEPARATOR.$file))
                return dirname($candidate);
        }

        throw new Exception('bench.yml could be found.');
    }

    /**
     * @return Definition
     */
    public function getContext(): Definition
    {
        return $this->registry->get('context');
    }

    /**
     * @param Definition $context
     */
    public function setContext(Definition $context)
    {
        $this->registry->set('context', $context);
    }

    /**
     * @return ExpressionHandler
     */
    public function getExpressionHandler(): ExpressionHandler
    {
        return $this->expressionHandler;
    }
}