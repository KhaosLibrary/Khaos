<?php

namespace Khaos\Bench\Tool\Bench;

use Auryn\Injector;
use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\DefinitionFactory\CompositeDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Bench\Tool\Bench\BenchFunctionRouter;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\BenchDefinitionFactory;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\CommandDefinitionFactory;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\ImportDefinitionFactory;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\NamespaceDefinitionFactory;
use Khaos\Bench\Tool\ToolFunctionRouter;
use Khaos\Bench\Tool\Tool;

class BenchTool implements Tool
{
    /**
     * @var BenchFunctionRouter
     */
    private $functionRouter;

    /**
     * @var CompositeDefinitionFactory
     */
    private $definitionFactory;

    /**
     * @var ResourceDefinitionRepository
     */
    private $definitionRepository;

    /**
     * @var ResourceDefinitionFieldParser
     */
    private $fieldParser;
    /**
     * @var Bench
     */
    private $bench;

    /**
     * BenchTool constructor.
     *
     * @param BenchFunctionRouter $functionRouter
     * @param CompositeDefinitionFactory $definitionFactory
     * @param ResourceDefinitionRepository $definitionRepository
     * @param ResourceDefinitionFieldParser $fieldParser
     * @param Bench $bench
     * @param Injector $injector
     */
    public function __construct(BenchFunctionRouter $functionRouter, CompositeDefinitionFactory $definitionFactory, ResourceDefinitionRepository $definitionRepository, ResourceDefinitionFieldParser $fieldParser, Bench $bench, Injector $injector)
    {
        $this->functionRouter       = $functionRouter;
        $this->definitionFactory    = $definitionFactory;
        $this->definitionRepository = $definitionRepository;
        $this->fieldParser          = $fieldParser;
        $this->bench                = $bench;

        $this->bootstrap($functionRouter, $definitionFactory, $injector);
    }

    public function getManifest()
    {
        return __DIR__.'/_assets/resources/manifest.yml';
    }

    /**
     * @return ToolFunctionRouter|null
     */
    public function getToolFunctionRouter()
    {
        return $this->functionRouter;
    }

    /**
     * Import Resources
     *
     * @param array $resourceDefinitionData
     */
    public function import(array $resourceDefinitionData)
    {
        $resourceDefinitionData['resource'] = $resourceDefinitionData['resource'] ?? ImportDefinition::TYPE;
        $this->definitionRepository->import($this->definitionFactory->create($resourceDefinitionData));
    }

    /**
     * @return array
     */
    public static function resources()
    {
        return [
            'bench',
            'bench/import',
            'bench/command',
            'bench/namespace'
        ];
    }

    /**
     * @param BenchFunctionRouter         $functionRouter
     * @param CompositeDefinitionFactory  $definitionFactory
     * @param Injector                    $injector
     */
    private function bootstrap(BenchFunctionRouter $functionRouter, CompositeDefinitionFactory $definitionFactory, Injector $injector)
    {
        require __DIR__ . '/_assets/config/events.php';
        require __DIR__ . '/_assets/config/di.php';

        $this->fieldParser->addValue('bench', $functionRouter);

        $definitionFactory->add($injector->make(BenchDefinitionFactory::class));
        $definitionFactory->add($injector->make(ImportDefinitionFactory::class));
        $definitionFactory->add($injector->make(CommandDefinitionFactory::class));
        $definitionFactory->add($injector->make(NamespaceDefinitionFactory::class));
    }
}