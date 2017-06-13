<?php

namespace Khaos\Bench\Tool\Shell;

use Khaos\Bench\Resource\ResourceDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Bench\Tool\Tool;
use Khaos\Bench\Tool\ToolFunctionRouter;

class ShellTool implements Tool
{
    /**
     * @var ResourceDefinitionFactory
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
     * @var ShellFunctionRouter
     */
    private $functionRouter;

    public function __construct(ResourceDefinitionFactory $definitionFactory, ResourceDefinitionRepository $definitionRepository, ResourceDefinitionFieldParser $fieldParser, ShellFunctionRouter $functionRouter)
    {
        $this->fieldParser          = $fieldParser;
        $this->definitionFactory    = $definitionFactory;
        $this->definitionRepository = $definitionRepository;
        $this->functionRouter       = $functionRouter;

        $this->fieldParser->addValue('shell', $functionRouter);
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
        $this->definitionRepository->import($this->definitionFactory->create($resourceDefinitionData));
    }

    /**
     * @return string|null
     */
    public function getManifest()
    {
        return null;
    }

    /**
     * @return array
     */
    public static function resources()
    {
        return [];
    }
}