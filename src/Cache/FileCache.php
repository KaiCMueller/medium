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
     * @return int
     */
    public function isOutdated()
    {
        return time() - filemtime($this->config->getFileCachePath()) > $this->config->getCacheTime();
    }

}
