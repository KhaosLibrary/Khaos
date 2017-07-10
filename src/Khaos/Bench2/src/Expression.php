<?php

namespace Khaos\Bench2;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Expression
{
    /**
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    /**
     * @var array
     */
    private $values = [];

    /**
     * Expression constructor.
     *
     * @param ExpressionLanguage $expressionLanguage
     */
    public function __construct(ExpressionLanguage $expressionLanguage = null)
    {
        $this->expressionLanguage = $expressionLanguage ?? new ExpressionLanguage();
    }

    /**
     * Add Value
     *
     * @param $name
     * @param $value
     */
    public function addValue($name, $value)
    {
        $this->values[$name] = $value;
    }

    /**
     * Add Function
     *
     * @param string $name
     * @param callable $compiler
     * @param callable $evaluator
     */
    public function addFunction(string $name, callable $compiler, callable $evaluator)
    {
        $this->expressionLanguage->register($name, $compiler, $evaluator);
    }

    /**
     * Evaluate
     *
     * @param $expression
     * @param array $values
     *
     * @return mixed
     */
    public function evaluate($expression, $values = [])
    {
        return $this->expressionLanguage->evaluate($expression, $values + $this->values);
    }
}