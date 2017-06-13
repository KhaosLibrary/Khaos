<?php

namespace Khaos\Bench;

use Exception;
use Auryn\Injector;
use InvalidArgumentException;
use Khaos\Bench\Command\CommandRunner;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use Khaos\Bench\Tool\Bench\BenchTool;
use Khaos\Bench\Tool\Docker\DockerTool;
use Khaos\Bench\Tool\Shell\ShellTool;
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
        'docker' => DockerTool::class,
        'shell'  => ShellTool::class
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
        if (($resourceDefinitions = $this->definitionLoader->load($source)) !== null)
        {
            foreach ($resourceDefinitions as $resourceDefinitionData)
                $this->tool($this->resourceToolMap[$resourceDefinitionData['resource'] ?? 'bench'])->import($resourceDefinitionData);

            return;
        }

        throw new InvalidArgumentException("Unable to import resource definitions from '{$source}';");
    }

    public function run(array $args = [])
    {
        if (count($args) == 1)
            $args[] = '--help';

        $shell = $this->tool('shell');

        $this->commandRunner->run($args);
    }

    /**
     * @param string $toolName
     *
     * @return Tool
     */
    public function tool($toolName)
    {
        if (!isset($this->tools[$toolName])) {

            $tool = $this->tools[$toolName] = $this->injector->make($this->toolClassMap[$toolName]);

            /** @var Tool $tool */

            if (($manifest = $tool->getManifest()) !== null)
                $this->import($manifest);

            return $tool;
        }

        return $this->tools[$toolName];
    }

    private function buildResourceToolMap()
    {
        foreach ($this->toolClassMap as $tool => $class)
            foreach ($class::resources() as $resourceType)
                $this->resourceToolMap[$resourceType] = $tool;
    }

    public static function getRootResourceDefinition($search, $file = 'bench.yml')
    {
        $search = $search.DIRECTORY_SEPARATOR;
        $length = strlen($search) + 1;
        $offset = $length;

        while (($offset = strrpos($search, DIRECTORY_SEPARATOR, $offset - $length)) !== false)
        {
            if (file_exists($candidate = substr($search, 0, $offset).DIRECTORY_SEPARATOR.$file))
                return $candidate;
        }

        throw new Exception('No root bench.yml could be found.');
    }
}
