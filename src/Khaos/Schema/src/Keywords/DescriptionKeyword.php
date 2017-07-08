<?php

namespace Khaos\Schema\Keywords;

use Khaos\Schema\Keyword;

class DescriptionKeyword implements Keyword
{
    const KEYWORD = 'description';

    public function getKeyword()
    {
        return self::KEYWORD;
    }

    public function validate(&$schema, &$instance)
    {
        return true;
    }
}