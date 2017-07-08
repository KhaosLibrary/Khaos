<?php

namespace Khaos\Schema;

use ArrayIterator;
use Exception;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class FileDataProvider implements DataProvider
{
    /**
     * @var SplFileInfo
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

        $this->file = new SplFileInfo($file);
    }


    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        $instances = null;

        if ($this->file->getExtension() == 'json')
            $instances = json_decode(file_get_contents($this->file->getPathname()));

        if ($this->file->getExtension() == 'yaml')
            foreach ($this->getYamlDocuments(file_get_contents($this->file)) as $document)
                $instances[] = Yaml::parse($document);

        if ($instances !== null)
            return new ArrayIterator($instances);

        throw new Exception();
    }

    /**
     * @param string $yaml
     *
     * @return array
     */
    private function getYamlDocuments($yaml)
    {
        return preg_split('/\R---\R/', $yaml);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'file:'.$this->file->getPathname();
    }

    /**
     * @return int
     */
    public function getLastModified()
    {
        return $this->file->getMTime();
    }
}