<?php

namespace Khaos\Bench\Resource\Type;

interface TypeRepository
{
    /**
     * Add ValidatorType
     *
     * @param Type $type
     *
     * @return void
     */
    public function add(Type $type);

    /**
     * Get ValidatorType
     *
     * @param $key
     *
     * @return Type
     */
    public function __get($key);
}