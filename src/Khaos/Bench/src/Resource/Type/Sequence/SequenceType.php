<?php


namespace Khaos\Bench\Resource\Type\Sequence;

use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\Type;
use Khaos\Bench\Resource\Type\TypeRepository;

class SequenceType implements Type
{
    const NAME = 'sequence';

    /**
     * @var TypeRepository
     */
    private $type;

    /**
     * MapType constructor.
     *
     * @param TypeRepository $typeResolver
     */
    public function __construct(TypeRepository $typeResolver)
    {
        $this->type = $typeResolver;
    }

    /**
     * Value
     *
     * @param array      $schema
     * @param array      $data
     * @param Definition $definition
     *
     * @return array
     */
    public function value(array $schema, $data, Definition $definition)
    {
        $sequence = [];
        $type     = $this->type->{$schema['sequence']['type']};
        $schema   = $schema['sequence'];

        foreach ($data as $sequenceItemData) {
            $sequence[] = $type->value($schema, $sequenceItemData, $definition);
        }

        return $sequence;
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
        return false;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function export(array $schema, $data)
    {
        $sequenceExportCollection = [];
        $type     = $this->type->{$schema['sequence']['type']};
        $schema   = $schema['sequence'];

        foreach ($data as $value)
            $sequenceExportCollection[] = $type->export($schema, $value);

        return '['.implode(',', $sequenceExportCollection).']';
    }
}