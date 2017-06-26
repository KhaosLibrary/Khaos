<?php

namespace Khaos\Cache;

class FileCacheItemPool implements CacheItemPool
{
    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var CacheItem[]
     */
    private $pool = [];

    /**
     * FileCacheItemPool constructor.
     *
     * @param $cacheDir
     */
    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;

        if (!is_dir($this->cacheDir))
            mkdir($this->cacheDir, 0755, true);
    }

    /**
     * @inheritdoc
     */
    public function get($key, $default = null)
    {
        if (isset($this->pool[$key]))
            return $this->pool[$key];

        $value = $default;
        $isHit = false;

        if (file_exists($cacheItemValue = $this->cacheDir.'/'.$key.'.cache.php')) {
            $value = include $cacheItemValue;
            $isHit = true;
        }

        return $this->pool[$key] = new FileCacheItem($key, $value, $isHit);
    }

    /**
     * @inheritdoc
     */
    public function save(CacheItem $cacheItem)
    {
        file_put_contents(
            $this->cacheDir.'/'.$cacheItem->getKey().'.cache.php',
            "<?php\n\nreturn ".$cacheItem->value().';'
        );
    }

    public function __destruct()
    {
        foreach ($this->pool as $cacheItem)
        {
            if ($cacheItem->isInvalidated())
                $this->save($cacheItem);
        }
    }
}