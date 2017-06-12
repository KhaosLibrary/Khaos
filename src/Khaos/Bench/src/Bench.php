<?php

namespace Khaos\Bench;

use Exception;
use Auryn\Injector;
use Khaos\Bench\Command\CommandRunner;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use Khaos\Bench\Tool\Bench\BenchTool;
use Khaos\Bench\Tool\Docker\DockerTool;
use Khaos\Bench\Tool\Tool;

class Bench
{
    const VERSION = '0.0.1';

    /**
     * @var CommandRunner
     */
    private $commandRunner;

    /**
     * @var Tool[]
     */
    private $tools = [];

    /**
     * @var Tool[]
     */
    private $toolClassMap = [
        'bench'  => BenchTool::class,
        'docker' => DockerTool::class
    ];

    private $resourceToolMap = [];

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
     * @param CommandRunner             $commandRunner
     * @param Injector                  $injector
     * @param ResourceDefinitionLoader  $definitionLoader
     */
    public function __construct(CommandRunner $commandRunner, Injector $injector, ResourceDefinitionLoader $definitionLoader)
    {
        $this->injector          = $injector;
        $this->commandRunner     = $commandRunner;
        $this->definitionLoader  = $definitionLoader;

        $this->buildResourceToolMap();
    }

    /**
     * @param $source
     */
    public function import($source)
    {
        foreach ($this->definitionLoader->load($source) as $resourceDefinitionData)
            $this->tool($this->resourceToolMap[$resourceDefinitionData['resource'] ?? 'bench'])->import($resourceDefinitionData);
    }

    public function run(array $args = [])
    {
        $this->commandRunner->run($args);
    }

    /**
     * @param string $tool
     *
     * @return Tool
     */
    public function tool($tool)
    {
        if (!isset($this->tools[$tool]))
            $this->tools[$tool] = $this->injector->make($this->toolClassMap[$tool]);

        return $this->tools[$tool];
    }

    private function buildResourceToolMap()
    {
        foreach ($this->toolClassMap as $tool => $class)
            foreach ($class::resources() as $resourceType)
                $this->resourceToolMap[$resourceType] = $tool;
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

        throw new Exception('No root bench.yml could be found.');
    }
}
