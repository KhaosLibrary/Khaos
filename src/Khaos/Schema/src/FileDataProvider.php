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
     * @var SchemaInstanceValidator
     */
    private $validator;

    /**
     * FileDataProvider constructor.
     *
     * @param string $file
     * @param SchemaInstanceValidator $validator
     *
     * @throws Exception
     */
    public function __construct($file, SchemaInstanceValidator $validator)
    {
        if (!file_exists($file))
            throw new Exception();

        $this->validator = $validator;
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
}