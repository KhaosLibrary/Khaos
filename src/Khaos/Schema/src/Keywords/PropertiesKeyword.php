<?php

namespace Khaos\Schema\Keywords;

use Khaos\Schema\Keyword;

class PropertiesKeyword implements Keyword
{
    const KEYWORD = 'properties';

    public function getKeyword()
    {
        return self::KEYWORD;
    }

    public function validate(&$schema, &$instance)
    {
        return true;
    }
}