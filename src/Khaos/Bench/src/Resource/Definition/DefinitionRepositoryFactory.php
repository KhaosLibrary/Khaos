<?php

namespace Khaos\Bench\Resource\Definition;

interface DefinitionRepositoryFactory
{
    /**
     * Create Definition Repository
     *
     * @param array $data
     *
     * @return DefinitionRepository
     */
    public function create($data = []);
}