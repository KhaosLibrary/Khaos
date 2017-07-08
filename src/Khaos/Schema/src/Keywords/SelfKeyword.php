<?php

namespace Khaos\Schema\Keywords;

use Khaos\Schema\Keyword;

class SelfKeyword implements Keyword
{
    const KEYWORD = 'self';

    public function getKeyword()
    {
        return self::KEYWORD;
    }

    public function validate(&$schema, &$instance)
    {
        return true;
    }
}