<?php

namespace Khaos\Bench\Resource\Definition;


use Khaos\Bench\Resource\Schema\SchemaRepository;
use Khaos\Bench\Resource\Type\TypeRepository;

class DefaultDefinitionRepositoryFactory implements DefinitionRepositoryFactory
{
    /**
     * @var TypeRepository
     */
    private $typeRepository;

    /**
     * @var SchemaRepository
     */
    private $schemaRepository;

    /**
     * DefaultDefinitionRepositoryFactory constructor.
     *
     * @param TypeRepository   $typeRepository
     * @param SchemaRepository $schemaRepository
     */
    public function __construct(TypeRepository $typeRepository, SchemaRepository $schemaRepository)
    {
        $this->typeRepository   = $typeRepository;
        $this->schemaRepository = $schemaRepository;
    }

    /**
     * @inheritdoc
     */
    public function create($data = [])
    {
        return new DefaultDefinitionRepository($this->typeRepository, $this->schemaRepository, $data);
    }
}