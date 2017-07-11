<?php

namespace Khaos\Schema\Keywords;

use Khaos\Schema\Keyword;

class ItemsKeyword implements Keyword
{
    const KEYWORD = 'items';

    public function getKeyword()
    {
        return self::KEYWORD;
    }

    public function validate(&$schema, &$instance)
    {
        return true;
    }
}