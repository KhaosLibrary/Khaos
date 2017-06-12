<?php

namespace Khaos\Bench\Tool\Bench;

use Auryn\Injector;
use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\DefinitionFactory\CompositeDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Bench\Tool\Bench\BenchFunctionRouter;
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
     * BenchTool constructor.
     *
     * @param BenchFunctionRouter           $functionRouter
     * @param CompositeDefinitionFactory    $definitionFactory
     * @param ResourceDefinitionRepository  $definitionRepository
     * @param ResourceDefinitionFieldParser $fieldParser
     */
    public function __construct(BenchFunctionRouter $functionRouter, CompositeDefinitionFactory $definitionFactory, ResourceDefinitionRepository $definitionRepository, ResourceDefinitionFieldParser $fieldParser)
    {
        $this->functionRouter       = $functionRouter;
        $this->definitionFactory    = $definitionFactory;
        $this->definitionRepository = $definitionRepository;
        $this->fieldParser          = $fieldParser;

        $this->fieldParser->addValue('bench', $functionRouter);
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
}