<?php

namespace Khaos\Bench;

use Khaos\Bench\Command\CommandRunner;
use Khaos\Bench\Resource\Definition\BenchDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Bench\Tool\ToolFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Bench
{
    const VERSION = 'Bench 0.0.2';

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var ResourceDefinitionRepository
     */
    private $definitions;

    /**
     * @var ToolFactory
     */
    private $toolFactory;

    /**
     * @var CommandRunner
     */
    private $commandRunner;

    /**
     * @var ResourceDefinitionFieldParser
     */
    private $definitionFieldParser;

    /**
     * Bench constructor.
     *
     * @param EventDispatcher $eventDispatcher
     * @param ResourceDefinitionRepository $resourceDefinitionRepository
     * @param ToolFactory $toolFactory
     * @param CommandRunner $commandRunner
     * @param ResourceDefinitionFieldParser $definitionFieldParser
     */
    public function __construct(EventDispatcher $eventDispatcher, ResourceDefinitionRepository $resourceDefinitionRepository, ToolFactory $toolFactory, CommandRunner $commandRunner, ResourceDefinitionFieldParser $definitionFieldParser)
    {
        $this->eventDispatcher       = $eventDispatcher;
        $this->definitions           = $resourceDefinitionRepository;
        $this->toolFactory           = $toolFactory;
        $this->commandRunner         = $commandRunner;
        $this->definitionFieldParser = $definitionFieldParser;
    }

    /**
     * @param $source
     */
    public function import($source)
    {
        $this->definitions->import($source);
    }

    // bench [options] <command>
    public function run(array $args = [])
    {
        $this->prepareBenchTools();
        $this->commandRunner->run($args);
    }

    public static function getRootResourceDefinition($search, $file = 'bench.yml')
    {
        $search = explode('/', substr($search, 1));

        for ($i = count($search); $i > 0; --$i)
        {
            $candidate = '/' . implode('/', array_slice($search, 0, $i)) . '/' . $file;

            if (file_exists($candidate))
                return $candidate;
        }

        return [
            'resource' => BenchDefinition::TYPE,
            'metadata' => [
                'title' => 'Global Bench',
                'description' => 'Bench is running in the global scope of the system.'
            ],
            'tools' => []
        ];
    }

    private function prepareBenchTools()
    {
        $this->definitionFieldParser->addValue('bench', $this);

        /** @var BenchDefinition[] $benchDefinitions */
        $benchDefinitions = $this->definitions->findByType(BenchDefinition::TYPE);

        foreach ($benchDefinitions as $benchDefinition)
            foreach ($benchDefinition->getTools() as $tool)
                $this->definitionFieldParser->addValue($tool, $this->toolFactory->create($tool));
    }

    public function version()
    {
        return self::VERSION;
    }
}
