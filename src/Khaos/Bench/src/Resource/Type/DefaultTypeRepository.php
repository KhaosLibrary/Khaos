<?php

namespace Khaos\Bench\Resource\Type;

use Exception;
use Khaos\Bench\Resource\Type\Type;
use Khaos\Bench\Resource\Type\TypeRepository;

class DefaultTypeRepository implements TypeRepository {

    /**
     * @var Type[]
     */
    private $type = [];

    /**
     * @param Type $type
     */
    public function add(Type $type)
    {
        $this->type[$type->getName()] = $type;
    }

    /**
     * @param string $key
     *
     * @return Type
     *
     * @throws Exception
     */
    public function __get($key)
    {
        if (!isset($this->type[$key]))
            throw new Exception();

        return $this->{$key} = $this->type[$key];
    }
}