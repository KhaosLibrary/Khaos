<?php

namespace Khaos\Bench2\Schema\Keyword;

use Khaos\Schema\Keyword;

class ExpressionKeyword implements Keyword
{
    const KEYWORD = 'expression';

    public function getKeyword()
    {
        return self::KEYWORD;
    }

    public function validate(&$schema, &$instance)
    {
        return true;
    }
}