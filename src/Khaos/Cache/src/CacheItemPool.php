<?php

namespace Khaos\Cache;

interface CacheItemPool
{
    /**
     * Get Cache Item
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return CacheItem
     */
    public function get($key, $default = null);

    /**
     * Save Cache Item
     *
     * @param CacheItem $cacheItem
     *
     * @return void
     */
    public function save(CacheItem $cacheItem);
}