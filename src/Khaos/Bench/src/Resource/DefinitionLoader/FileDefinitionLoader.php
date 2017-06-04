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
     * @return ResourceDefinition[]|null
     */
    public function load($source)
    {
        if (!file_exists($source))
            return null;

        $fileInfo = new SplFileInfo($source);

        if (!isset($this->definitionLoaders[$fileInfo->getExtension()]))
            return null;

        $resources = $this->definitionLoaders[$fileInfo->getExtension()]->load(file_get_contents($source));
        $resources = $this->updateResourceWorkingDirectory(realpath(dirname($source)), $resources);

        return $resources;
    }

    /**
     * @param string $workingDirectory
     * @param ResourceDefinition[] $resources
     *
     * @return ResourceDefinition[]
     */
    private function updateResourceWorkingDirectory($workingDirectory, $resources)
    {
        foreach ($resources as $resourceDefinition) {

            $resourceWorkingDirectory = $resourceDefinition->getWorkingDirectory();

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

            $resourceDefinition->setMetaData('working-directory', realpath($resourceWorkingDirectory));
        }

        return $resources;
    }
}
