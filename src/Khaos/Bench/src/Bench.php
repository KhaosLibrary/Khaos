<?php

namespace Khaos\Bench;

use Auryn\Injector;
use Khaos\Bench\Command\CommandRunner;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use Khaos\Bench\Tool\Bench\BenchTool;
use Khaos\Bench\Tool\Bench\Resource\Definition\BenchDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Bench\Tool\Docker\DockerTool;
use Khaos\Bench\Tool\Tool;
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
     * @var CommandRunner
     */
    private $commandRunner;

    /**
     * @var ResourceDefinitionFieldParser
     */
    private $definitionFieldParser;

    /**
     * @var Tool[]
     */
    private $tools = [];

    private $toolClassMap = [
        'bench'  => BenchTool::class,
        'docker' => DockerTool::class
    ];
    /**
     * @var Injector
     */
    private $injector;
    /**
     * @var ResourceDefinitionLoader
     */
    private $definitionLoader;

    /**
     * Bench constructor.
     *
     * @param EventDispatcher $eventDispatcher
     * @param ResourceDefinitionRepository $resourceDefinitionRepository
     * @param CommandRunner $commandRunner
     * @param ResourceDefinitionFieldParser $definitionFieldParser
     * @param Injector $injector
     * @param ResourceDefinitionLoader $definitionLoader
     */
    public function __construct(EventDispatcher $eventDispatcher, ResourceDefinitionRepository $resourceDefinitionRepository, CommandRunner $commandRunner, ResourceDefinitionFieldParser $definitionFieldParser, Injector $injector, ResourceDefinitionLoader $definitionLoader)
    {
        $this->eventDispatcher       = $eventDispatcher;
        $this->definitions           = $resourceDefinitionRepository;
        $this->commandRunner         = $commandRunner;
        $this->definitionFieldParser = $definitionFieldParser;
        $this->injector              = $injector;
        $this->definitionLoader      = $definitionLoader;
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

    public function tool($tool)
    {
        return $this->tools[$tool];
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
        $tools = [];

        /** @var BenchDefinition[] $benchDefinitions */
        $benchDefinitions = $this->definitions->findByType(BenchDefinition::TYPE);

        foreach ($benchDefinitions as $benchDefinition)
            foreach ($benchDefinition->getTools() as $tool)
                $tools[] = $tool;

        $tools = array_unique($tools);

        foreach ($tools as $tool)
        {
            $this->tools[$tool] = $this->injector->make($this->toolClassMap[$tool]);
            $this->definitionFieldParser->addValue($tool, $this->tools[$tool]->getToolFunctionRouter());
        }
    }
}
