<?php

namespace Khaos\Bench\Resource\Definition;

interface Definition
{
    /**
     * Get Definition Value
     *
     * Definitions are basically Map types with a pre-defined schema, using __get should retrieve
     * the appropriate value using the TypeResolver and cache it to the object.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key);

    /**
     * Match Definition
     *
     * @param array $match
     *
     * @return bool
     */
    public function match($match);

    /**
     * @return string
     */
    public function export();

    /**
     * @return string
     */
    public function getWorkingDirectory();

    /**
     * @return string
     */
    public function getSourceFile();
}