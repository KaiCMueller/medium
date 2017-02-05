<?php

namespace KaiCMueller\Medium\Cache;

interface CacheInterface
{

    /**
     * @return string
     */
    public function read();

    /**
     * @param string $data
     * @return void
     */
    public function write($data);

    /**
     * @return bool
     */
    public function isOutdated();

}
