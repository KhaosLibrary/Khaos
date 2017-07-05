<?php

namespace Khaos\Schema\Keywords;

use Exception;
use Khaos\Schema\Keyword;

class TypeKeyword implements Keyword
{
    const KEYWORD = 'type';

    public function validate(&$schema, &$instance)
    {
        return $this->{'validate_'.$schema['type']}($schema, $instance);
    }

    private function validate_string(&$schema, &$instance)
    {
        if (is_string($instance))
            return true;

        if (is_object($instance) && method_exists($instance, '__toString'))
            return true;

        return false;
    }

    private function validate_number(&$schema, &$instance)
    {
        return is_numeric($instance);
    }

    private function validate_object(&$schema, &$instance)
    {
        if (is_array($instance))
            $instance = (object)$instance;

        return is_object($instance);
    }

    private function validate_array(&$schema, &$instance)
    {
        return is_array($instance);
    }

    private function validate_boolean(&$schema, &$instance)
    {
        return is_bool($instance);
    }

    private function validate_null(&$schema, &$instance)
    {
        return is_null($instance);
    }

    public function __call($type, $args)
    {
        throw new Exception();
    }

    public function getKeyword()
    {
        return self::KEYWORD;
    }
}