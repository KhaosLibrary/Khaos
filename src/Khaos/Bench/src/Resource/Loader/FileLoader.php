<?php

namespace Khaos\Bench\Resource\Loader;

use SplFileInfo;

class FileLoader implements Loader
{
    /**
     * File ValidatorType Loaders
     *
     * @var Loader[]
     */
    private $fileTypeLoader = [];

    /**
     * Add
     *
     * @param array|string $extensions
     * @param Loader       $loader
     */
    public function add($extensions, Loader $loader)
    {
        if (!is_array($extensions))
            $extensions = [$extensions];

        foreach ($extensions as $extension)
            $this->fileTypeLoader[$extension] = $loader;
    }

    /**
     * @param $source
     *
     * @return array
     */
    public function load($source)
    {
        $fileInfo    = new SplFileInfo($source);
        $fileContent = file_get_contents($fileInfo);

        $data = [];

        foreach ($this->fileTypeLoader[$fileInfo->getExtension()]->load($fileContent) as $value)
        {
            $value['metadata']['working-directory'] = dirname($fileInfo->getRealPath());
            $value['metadata']['source-file']       = $fileInfo->getRealPath();

            $data[] = $value;
        }

        return $data;
    }
}