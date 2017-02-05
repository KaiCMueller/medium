<?php

namespace KaiCMueller\Medium;

use KaiCMueller\Medium\Cache\CacheInterface;
use KaiCMueller\Medium\Exception\CacheException;

class Cache
{

    /**
     * @var \KaiCMueller\Medium\Cache\CacheInterface
     */
    protected $cache;

    /**
     * @var \KaiCMueller\Medium\Config
     */
    protected $config;

    public function __construct(CacheInterface $cache, Config $config)
    {
        $this->config = $config;
        $this->set($cache);
    }

    /**
     * @param \KaiCMueller\Medium\Cache\CacheInterface $cache
     */
    protected function set(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return string
     * @throws \KaiCMueller\Medium\Exception\CacheException
     */
    public function read()
    {
        try {
            return $this->cache->read();
        } catch (\Exception $e) {
            throw new CacheException("Can not read from cache");
        }
    }

    /**
     * @param string $data
     * @throws \KaiCMueller\Medium\Exception\CacheException
     */
    public function write($data)
    {
        try {
            return $this->cache->write($data);
        } catch (\Exception $e) {

            throw new CacheException("Can not write to cache");
        }
    }

    /**
     * @return bool
     * @throws \KaiCMueller\Medium\Exception\CacheException
     */
    public function isOutdated()
    {
        try {
            return $this->cache->isOutdated();
        } catch (\Exception $e) {

            throw new CacheException("Can not check cache date");
        }
    }

}
