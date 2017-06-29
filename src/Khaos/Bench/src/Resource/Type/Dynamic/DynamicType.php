<?php

namespace Khaos\Bench\Resource\Type\Dynamic;

use Exception;
use Khaos\Bench\Resource\Definition\Definition;
use Khaos\Bench\Resource\Type\Type;
use Khaos\Bench\Resource\Type\TypeRepository;

class DynamicType implements Type
{
    const NAME = 'dynamic';

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
     * @inheritdoc
     */
    public function value(array $schema, $data, Definition $definition)
    {
        if (!isset($data['_schema']))
            $data = [
                '_schema' => $this->generateSchema($data),
                '_data'   => $data
            ];

        $schema = $data['_schema'];
        $data   = $data['_data'];

        return $this->type->{$schema['type']}->value($schema, $data, $definition);

    }

    /**
     * @inheritdoc
     */
    public function export(array $schema, $data)
    {
        if (!isset($data['_schema']))
            $data = [
                '_schema' => $this->generateSchema($data),
                '_data'   => $data
            ];

        $schema = $data['_schema'];
        $data   = $data['_data'];

        $schemaExport = var_export($schema, true);

        return '["_schema" => '.$schemaExport.', "_data" => '.$this->type->{$schema['type']}->export($schema, $data).']';
    }

    /**
     * @inheritdoc
     */
    public function match(array $schema, $match, $against)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param mixed $data
     *
     * @return array
     * @throws Exception
     */
    private function generateSchema($data)
    {
        if (is_string($data))
            return $this->generateStringSchema();

        if (is_array($data) && isset($data[0]))
            return $this->generateSequenceSchema($data);

        if (is_array($data))
            return $this->generateMapSchema($data);

        throw new Exception();
    }

    /**
     * @return array
     */
    private function generateStringSchema()
    {
        return ['type' => 'inline-expression'];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function generateSequenceSchema($data)
    {
        $schema = [
            'type'     => 'sequence',
            'sequence' => $this->generateSchema($data[0])
        ];

        return $schema;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function generateMapSchema($data)
    {
        $schema = [
            'type' => 'map',
            'map'  => []
        ];

        foreach ($data as $key => $value)
            $schema['map'][$key] = $this->generateSchema($value);

        return $schema;
    }
}