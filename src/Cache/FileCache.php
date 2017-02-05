<?php

namespace KaiCMueller\Medium\Cache;

use KaiCMueller\Medium\Config;

class FileCache implements CacheInterface
{

    /**
     * @var \KaiCMueller\Medium\Config
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function read()
    {
        if (!file_exists($this->config->getFileCachePath())) {

            return '';
        }
        return file_get_contents($this->config->getFileCachePath());
    }

    /**
     * @param string $data
     */
    public function write($data)
    {
        file_put_contents($this->config->getFileCachePath(), $data);
    }

    /**
     * @return bool
     */
    public function isOutdated()
    {
        if (!file_exists($this->config->getFileCachePath())) {

            return true;
        }
        return time() - filemtime($this->config->getFileCachePath()) > $this->config->getCacheTime();
    }

}
