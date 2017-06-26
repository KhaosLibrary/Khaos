<?php

namespace Khaos\Bench\Resource\Type;

interface TypeRepository
{
    /**
     * Add Type
     *
     * @param Type $type
     *
     * @return void
     */
    public function add(Type $type);

    /**
     * Get Type
     *
     * @param $key
     *
     * @return Type
     */
    public function __get($key);
}