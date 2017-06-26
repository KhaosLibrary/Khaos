<?php

namespace Khaos\Bench\Resource\Type;


use Khaos\Bench\Resource\Definition\Definition;

interface Type {
    public function value(array $schema, $data, Definition $definition);
    public function export(array $schema, $data);
    public function match(array $schema, $match, $against);
    public function getName();
}