<?php

namespace Khaos\Schema;

use Exception;
use SplFileInfo;

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
    public function get($id)
    {

    }

    /**
     * 
     */
    private function getInstancesFromFile()
    {

    }
}