<?php
/**
 * Created by PhpStorm.
 * User: dcole
 * Date: 24/06/17
 * Time: 11:21
 */

namespace Khaos\Cache;


class FileCacheItem implements CacheItem
{
    /**
     * @var
     */
    private $data;

    /**
     * @var
     */
    private $key;

    /**
     * @var
     */
    private $isHit;

    /**
     * @var
     */
    private $isInvalidated;

    /**
     * FileCacheItem constructor.
     *
     * @param string $key
     * @param mixed  $data
     * @param bool   $isHit
     */
    public function __construct($key, $data, $isHit)
    {
        $this->data = $data;
        $this->key  = $key;
        $this->isHit = $isHit;
    }

    /**
     * @inheritdoc
     */
    public function value()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function set($value)
    {
        $this->isInvalidated = true;
        $this->data          = $value;
    }

    public function getKey()
    {
        return $this->key;
    }

    /**
     * @inheritdoc
     */
    public function isHit()
    {
        return $this->isHit;
    }

    /**
     * @return bool
     */
    public function isInvalidated()
    {
        return $this->isInvalidated;
    }
}