<?php

namespace Khaos\Bench\Resource;

interface ResourceDefinitionFieldParser
{
    public function evaluate($expression, $values = []);
    public function addValue($name, $value);
}