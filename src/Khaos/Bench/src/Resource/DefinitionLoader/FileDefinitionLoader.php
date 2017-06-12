<?php

namespace Khaos\Bench\Resource\DefinitionLoader;

use InvalidArgumentException;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use SplFileInfo;

class FileDefinitionLoader implements ResourceDefinitionLoader
{
    /**
     * @var ResourceDefinitionLoader[]
     */
    private $definitionLoaders = [];

    /**
     * Add ResourceDefinitionLoader
     *
     * @param ResourceDefinitionLoader  $definitionLoader
     * @param array                     $fileExtensions
     *
     * @return void
     */
    public function add(ResourceDefinitionLoader $definitionLoader, array $fileExtensions)
    {
        foreach ($fileExtensions as $fileExtension)
            $this->definitionLoaders[$fileExtension] = $definitionLoader;
    }

    /**
     * @param mixed $source
     *
     * @return array|null
     */
    public function load($source)
    {
        if (!is_string($source) || !file_exists($source))
            return null;

        $fileInfo         = new SplFileInfo($source);
        $workingDirectory = realpath(dirname($source));

        if (!isset($this->definitionLoaders[$fileInfo->getExtension()]))
            return null;

        $resources = [];

        foreach ($this->definitionLoaders[$fileInfo->getExtension()]->load(file_get_contents($source)) as $resource)
            $resources[] = $this->updateResourceWorkingDirectory($workingDirectory, $resource);

        return $resources;
    }

    /**
     * @param string $workingDirectory
     * @param array  $resource
     *
     * @return array
     */
    private function updateResourceWorkingDirectory($workingDirectory, $resource)
    {
        $resourceWorkingDirectory = $resource['metadata']['working-directory'] ?? null;

        if ($resourceWorkingDirectory) {

            if ($resourceWorkingDirectory[0] == '/') {
                throw new InvalidArgumentException('Only relative working directories can be specified in file based resources.');
            }

            $resourceWorkingDirectory = $workingDirectory.'/'.$resourceWorkingDirectory;
        }
        else
        {
            $resourceWorkingDirectory = $workingDirectory;
        }

        $resource['metadata']['working-directory'] = realpath($resourceWorkingDirectory);


        return $resource;
    }
}
