<?php


namespace Khaos\Bench\Resource\Type\Map;

use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\Type;
use Khaos\Bench\Resource\Type\TypeRepository;

class MapType implements Type
{
    const NAME = 'map';

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
     * @return MapTypeValue
     */
    public function value(array $schema, $data, Definition $definition)
    {
        return new MapTypeValue($schema, $data, $this->type, $definition);
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
        foreach (array_keys($match) as $key)
        {
            if (!isset($schema['map'][$key]))
                return false;

            if (!$this->type->{$schema['map'][$key]['type']}->match($schema['map'][$key], $match[$key], $against->{$key}))
                return false;
        }

        return true;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function export(array $schema, $data)
    {
        $mapExportCollection = [];

        foreach ($data as $key => $value)
            $mapExportCollection[] = '"'.$key.'" => '.$this->type->{$schema['map'][$key]['type']}->export($schema['map'][$key], $value);

        return '['.implode(',', $mapExportCollection).']';
    }
}