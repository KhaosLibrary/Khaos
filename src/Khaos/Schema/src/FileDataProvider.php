<?php

namespace Khaos\Schema;

use Exception;
use Symfony\Component\Yaml\Yaml;

class FileDataProvider implements DataProvider
{
    /**
     * @var string
     */
    private $file;


    /**
     * FileDataProvider constructor.
     *
     * @param string $file
     *
     * @throws Exception
     */
    public function __construct($file)
    {
        if (!file_exists($file))
            throw new Exception();

        $this->file      = $file;
    }


    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        foreach ($this->getYamlDocuments(file_get_contents($this->file)) as $document)
        {
            $instance = Yaml::parse($document);
        }
    }

    /**
     * @return array
     */
    private function getYamlDocuments($yaml)
    {

    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function getLastModified()
    {
        // TODO: Implement getLastModified() method.
    }
}