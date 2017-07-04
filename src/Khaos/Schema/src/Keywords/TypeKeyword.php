<?php

namespace Khaos\Schema\Keywords;

use Exception;
use Khaos\Schema\ValidativeKeyword;

class TypeKeyword implements ValidativeKeyword
{
    const KEYWORD = 'type';

    public function validate(&$schema, &$instance)
    {
        return $this->{'validate_'.$schema['type']}($schema, $instance);
    }

    private function validate_string(&$schema, &$instance)
    {
        return is_string($instance);
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

    private function validate__dynamic(&$schema, &$instance)
    {
        if (is_array($instance) && !isset($instance[0]))
            $instance = (object)$instance;

        unset($schema['type']);

        return true;
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