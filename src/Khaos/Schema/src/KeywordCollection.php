<?php

namespace Khaos\Schema;

use Exception;

class KeywordCollection
{
    private $keywords = [];

    public function add(Keyword $keyword)
    {
        $this->keywords[$keyword->getKeyword()] = $keyword;
    }

    public function has($keyword)
    {
        return isset($this->keywords[$keyword]);
    }

    /**
     * @param string $keyword
     *
     * @return Keyword
     *
     * @throws Exception
     */
    public function __get($keyword)
    {
        if (!isset($this->keywords[$keyword]))
            throw new Exception();

        return $this->{$keyword} = $this->keywords[$keyword];
    }
}