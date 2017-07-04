<?php

namespace Khaos\Schema\Keywords;

use Khaos\Schema\ValidativeKeyword;
use stdClass;

class Expression
{
    public $expression;

    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    public function evaluate()
    {

    }

    public function __toString()
    {
        return $this->expression;
    }
}

class DecoratorKeyword implements ValidativeKeyword
{
    const KEYWORD = 'decorator';

    public function getKeyword()
    {
        return self::KEYWORD;
    }

    public function validate(&$schema, &$instance)
    {
        if ($schema['decorator'] != 'expression')
            return true;

        $schema['type'] = 'object';

        if (is_string($instance))
        {
            $expression = $instance;

            $instance = new stdClass();
            $instance->expression = $expression;
        }

        $expression = new Expression($instance->expression);
        $instance   = $expression;

        return true;
    }
}