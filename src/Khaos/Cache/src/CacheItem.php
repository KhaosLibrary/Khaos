<?php

namespace Khaos\Cache;

interface CacheItem
{
    /**
     * @return mixed
     */
    public function value();

    /**
     * @param $value
     * @return mixed
     */
    public function set($value);

    /**
     * @return mixed
     */
    public function getKey();

    /**
     * @return bool
     */
    public function isHit();

    /**
     * @return bool
     */
    public function isInvalidated();
}