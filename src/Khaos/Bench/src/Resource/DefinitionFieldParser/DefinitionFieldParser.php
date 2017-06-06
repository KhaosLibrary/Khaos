<?php

namespace Khaos\Bench\Resource\DefinitionFieldParser;

use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class DefinitionFieldParser implements ResourceDefinitionFieldParser
{
    /**
     * @var ExpressionLanguage
     */
    private $expressionParser;

    /**
     * @var array
     */
    private $values = [];

    /**
     * DefinitionFieldParser constructor.
     *
     * @param ExpressionLanguage $expressionParser
     */
    public function __construct(ExpressionLanguage $expressionParser)
    {
        $this->expressionParser = $expressionParser;
    }

    /**
     * Evaluate
     *
     * @param $expression
     * @param array $values
     *
     * @return string
     */
    public function evaluate($expression, $values = [])
    {
        return $this->expressionParser->evaluate($expression, array_merge($this->values, $values));
    }

    /**
     * @param $name
     * @param $value
     */
    public function addValue($name, $value)
    {
        $this->values[$name] = $value;
    }
}