<?php

namespace Khaos\Bench\Resource\Definition;

use Khaos\Bench\Resource\Schema\DefaultSchemaRepository;
use Khaos\Bench\Resource\Schema\SchemaRepository;
use Khaos\Bench\Resource\Type\TypeRepository;

/**
 * Class DefinitionRepository
 *
 * @package Khaos\Bench\Resource
 */
class DefaultDefinitionRepository implements DefinitionRepository
{
    /**
     * @var TypeRepository
     */
    private $typeRepository;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $queryCache = [];


    /**
     * @var SchemaRepository
     */
    private $schemaRepository;

    /**
     * StandardDefinitionRepository constructor.
     *
     * @param TypeRepository    $typeResolver
     * @param SchemaRepository  $schemaRepository
     * @param array             $data
     */
    public function __construct(TypeRepository $typeResolver, SchemaRepository $schemaRepository, $data = [])
    {
        $this->typeRepository     = $typeResolver;
        $this->schemaRepository = $schemaRepository;
        $this->data             = $data;
    }

    /**
     * @param array $data
     */
    public function add(array $data)
    {
        $this->data[$data['schema']][$data['metadata']['id']] = $data;
    }


    /**
     * Query
     *
     * @param array $match
     *
     * @return array
     */
    public function query($match)
    {
        $cacheKey = md5(serialize($match));

        if (isset($this->queryCache[$cacheKey]))
            return $this->queryCache[$cacheKey];

        $result = [];

        if (isset($match['schema']) && is_string($match['schema']))
        {
            $schema = $match['schema'];

            foreach (array_keys($this->data[$schema]) as $id)
            {
                $key        = $schema.':'.$id;
                $definition = $this->{$key};

                if (!$definition->match($match))
                    continue;

                $result[$key] = $definition;
            }
        }
        else
        {
            foreach ($this as $key => $definition)
            {
                if (!$definition->match($match))
                    continue;

                $result[$key] = $definition;
            }
        }

        return $this->queryCache[$cacheKey] = $result;
    }

    /**
     * @param string $key
     *
     * @return Definition
     */
    public function __get($key)
    {
        list($schema, $id)
            = explode(':', $key);

        return $this->{$key} = $this->schemaRepository->{$this->data[$schema][$id]['schema']}
          ->getDefinition($this->data[$schema][$id]);
    }

    /**
     * Get Iterator
     *
     * @return Definition[]
     */
    public function getIterator()
    {
        foreach (array_keys($this->data) as $schema)
        {
            foreach (array_keys($this->data[$schema]) as $id)
            {
                $key = $schema.':'.$id;
                yield $key => $this->{$key};
            }
        }
    }

    /**
     * @return string
     */
    public function export()
    {
        $repositoryExport       = '['."\n";
        $schemaExportCollection = [];

        foreach (array_keys($this->data) as $schema)
        {
            $schemaExport               = '  "'.$schema.'" => ['."\n";
            $definitionExportCollection = [];

            foreach (array_keys($this->data[$schema]) as $id)
                $definitionExportCollection[] = '    "'.$id.'" => '.$this->{$schema.':'.$id}->export();

            $schemaExport .= implode(",\n", $definitionExportCollection);
            $schemaExport .= "\n".'  ]';

            $schemaExportCollection[] = $schemaExport;
        }

        $repositoryExport .= implode(",\n", $schemaExportCollection);
        $repositoryExport .= "\n".']';

        return $repositoryExport;
    }

    /**
     * Count of the number of definitions of a given schema that are loaded
     *
     * @param string $schema   eg. 'docker/image'
     *
     * @return int
     */
    public function count($schema)
    {
        if (!isset($this->data[$schema]))
            return 0;

        return count($this->data[$schema]);
    }

    /**
     * @return SchemaRepository
     */
    public function getSchemaRepository()
    {
        return $this->schemaRepository;
    }

    /**
     * @return TypeRepository
     */
    public function getTypeRepository()
    {
        return $this->typeRepository;
    }
}