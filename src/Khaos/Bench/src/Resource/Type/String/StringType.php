<?php

namespace Khaos\Bench\Resource\Type\String;

use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\Type;
use Khaos\Bench\Resource\Type\TypeRepository;

class StringType implements Type
{
    const NAME = 'string';

    /**
     * @param array      $schema
     * @param string     $data
     * @param Definition $definition
     *
     * @return mixed
     */
    public function value(array $schema, $data, Definition $definition)
    {
        return $data;
    }

    public function match(array $schema, $match, $against)
    {
        return $match == $against;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function export(array $schema, $data)
    {
        return var_export($data, true);
    }
}