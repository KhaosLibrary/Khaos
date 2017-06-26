<?php

namespace Khaos\Bench\Resource\Type\Expression;

interface ExpressionHandler
{
    public function compile($expression, $names = []);
    public function evaluate($expression, $values = []);
    public function register($name, callable $compiler, callable $evaluator);
    public function addGlobalValue($name, $value);
    public function getGlobalValues();
}