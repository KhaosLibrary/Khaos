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

    /**
     * @param string $keyword
     *
     * @return ValidativeKeyword
     *
     * @throws Exception
     */
    public function __get($keyword)
    {
        if (!isset($this->keywords[$keyword]))
            throw new Exception();

        if ($this->keywords[$keyword] instanceof ValidativeKeyword)
            return $this->{$keyword} = $this->keywords[$keyword];

        if ($this->keywords[$keyword] instanceof InformativeKeyword)
            return $this->{$keyword} =
                new class ($keyword) implements ValidativeKeyword
                {
                    private $keyword;

                    public function __construct($keyword)
                    {
                        $this->keyword = $keyword;
                    }

                    public function validate(&$schema, &$instance)
                    {
                        return;
                    }

                    public function getKeyword()
                    {
                        return $this->keyword;
                    }
                };

        throw new Exception();
    }
}