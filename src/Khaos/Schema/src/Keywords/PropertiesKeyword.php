<?php

namespace Khaos\Schema\Keywords;

use Khaos\Schema\InformativeKeyword;

class PropertiesKeyword implements InformativeKeyword
{
    const KEYWORD = 'properties';

    public function getKeyword()
    {
        return self::KEYWORD;
    }
}