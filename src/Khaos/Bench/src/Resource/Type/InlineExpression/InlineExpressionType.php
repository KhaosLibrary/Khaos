<?php

namespace Khaos\Bench\Resource\Type\InlineExpression;

use Exception;
use Generator;
use Khaos\Bench\Registry;
use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\Expression\ExpressionHandler;
use Khaos\Bench\Resource\Type\Type;
use Khaos\Bench\Resource\Type\TypeRepository;

class InlineExpressionType implements Type
{
    const NAME = 'inline-expression';

    /**
     * @var TypeRepository
     */
    private $typeResolver;

    /**
     * @var ExpressionHandler
     */
    private $expressionHandler;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * ExpressionType constructor.
     *
     * @param TypeRepository     $typeResolver
     * @param ExpressionHandler  $expressionHandler
     * @param Registry           $registry
     */
    public function __construct(TypeRepository $typeResolver, ExpressionHandler $expressionHandler, Registry $registry)
    {
        $this->typeResolver      = $typeResolver;
        $this->expressionHandler = $expressionHandler;
        $this->registry          = $registry;
    }

    /**
     * @inheritdoc
     */
    public function value(array $schema, $data, Definition $definition)
    {
        $this->registry->set('context', $definition);

        $values         = $this->expressionHandler->getGlobalValues();
        $values['self'] = $definition;

        if (is_callable($data))
            return $data($values);

        if (is_string($data))
            return $this->evaluate($data, $values);

        throw new Exception();
    }

    /**
     * @inheritdoc
     */
    public function export(array $schema, $data)
    {
        if (!$this->containsExpressions($data))
            return var_export($data, true);

        $expression = $this->createFullExpression($data);

        $names   = array_keys($this->expressionHandler->getGlobalValues());
        $names[] = 'self';

        return 'function($values){extract($values); return '.$this->expressionHandler->compile($expression, $names).';}';

    }

    /**
     * @inheritdoc
     */
    public function match(array $schema, $match, $against)
    {
        return $match == $against;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Evaluate Inline Expression
     *
     * @param string $data
     * @param array  $values
     *
     * @return mixed
     */
    private function evaluate($data, $values)
    {
        if (!$this->containsExpressions($data))
            return $data;

        return $this->expressionHandler->evaluate($this->createFullExpression($data), $values);
    }

    /**
     * Contains Expression
     *
     * @param string $data
     *
     * @return bool
     */
    function containsExpressions($data)
    {
        return strpos($data, '<%') !== false;
    }

    /**
     * Get Tokens
     *
     * @param string $data
     *
     * @return Generator
     */
    private function getTokens($data)
    {
        $offset = 0;

        while (($current = strpos($data, '<%', $offset)) !== false)
        {
            if ($current != $offset)
                yield 'string' => substr($data, $offset, $current - $offset);

            $offset  = $current + 2;
            $current = strpos($data, '%>', $offset);

            yield 'expression' => trim(substr($data, $offset, $current - $offset));

            $offset = $current + 2;
        }

        if ($offset < strlen($data))
            yield 'string' => substr($data, $offset);
    }

    /**
     * @param string $data
     *
     * @return array
     */
    private function createFullExpression($data)
    {
        $expression = [];

        foreach ($this->getTokens($data) as $token => $value) {
            switch ($token) {
                case 'string':
                    $expression[] = var_export($value, true);
                    break;

                case 'expression':
                    $expression[] = '(' . $value . ')';
                    break;
            }
        }

        return implode(' + ', $expression);
    }
}