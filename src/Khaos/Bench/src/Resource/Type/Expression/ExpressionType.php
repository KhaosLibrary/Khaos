<?php

namespace Khaos\Bench\Resource\Type\Expression;

use Exception;
use Khaos\Bench\Registry;
use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\Type;
use Khaos\Bench\Resource\Type\TypeRepository;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionType implements Type
{
    const NAME = 'expression';

    /**
     * @var TypeRepository
     */
    private $type;

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
     * @param TypeRepository $typeResolver
     * @param ExpressionHandler $expressionHandler
     * @param Registry $registry
     */
    public function __construct(TypeRepository $typeResolver, ExpressionHandler $expressionHandler, Registry $registry)
    {
        $this->type              = $typeResolver;
        $this->expressionHandler = $expressionHandler;
        $this->registry          = $registry;
    }

    /**
     * Value
     *
     * @param array       $schema
     * @param mixed       $data
     * @param Definition  $definition
     *
     * @return string
     * @throws Exception
     */
    public function value(array $schema, $data, Definition $definition)
    {
        $this->registry->set('context', $definition);

        $values         = $this->expressionHandler->getGlobalValues();
        $values['self'] = $definition;

        if (is_callable($data))
            return $data($values);

        if (is_string($data))
            return $this->expressionHandler->evaluate($data, $values);

        throw new Exception();
    }

    /**
     * Match
     *
     * @param array $schema
     * @param mixed $match
     * @param mixed $against
     *
     * @return bool
     */
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
        $names   = array_keys($this->expressionHandler->getGlobalValues());
        $names[] = 'self';

        return 'function($values){extract($values); return '.$this->expressionHandler->compile($data, $names).';}';
    }
}