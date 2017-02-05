<?php

namespace KaiCMueller\Medium;

use KaiCMueller\Medium\Exception\ValidationException;

class Config
{

    const CONFIG_BASE_URL = 'https://medium.com/%s/';
    const CONFIG_URL_PATH = 'latest?format=json';

    const KEY_CACHE_TIME = 'cacheTime';
    const KEY_USER = 'user';
    const KEY_USE_FILE_CACHE = 'useFileCache';
    const KEY_FILE_CACHE_PATH = 'fileCachePath';

    const DEFAULT_CACHE_TIME = 300;
    const DEFAULT_USE_FILE_CACHE = false;

    /**
     * @var array
     */
    protected $configData = [];

    public function __construct(array $configData)
    {
        $this->set($configData);
    }

    /**
     * @param $configData
     */
    protected function set($configData)
    {
        $this->configData = $configData;

        $this->validate();
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function setDefault($key, $value)
    {
        $this->configData[$key] = $value;
    }

    /**
     * @return bool
     *
     * @throws \KaiCMueller\Medium\Exception\ValidationException
     */
    protected function validate()
    {
        if (!is_array($this->configData)) {

            throw new ValidationException('Configuration data invalid or missing');
        }

        if (
            !array_key_exists(self::KEY_CACHE_TIME, $this->configData)
            || !is_numeric($this->configData[self::KEY_CACHE_TIME])
        ) {

            $this->setDefault(self::KEY_CACHE_TIME, self::DEFAULT_CACHE_TIME);
        }

        if (
            !array_key_exists(self::KEY_USER, $this->configData)
            || !is_string($this->configData[self::KEY_USER])
        ) {

            $this->throwInvalidConfigDataException(self::KEY_USER);
        }

        if (
            !array_key_exists(self::KEY_USE_FILE_CACHE, $this->configData)
            || !is_bool($this->configData[self::KEY_USE_FILE_CACHE])
        ) {

            $this->setDefault(self::KEY_USE_FILE_CACHE, self::DEFAULT_USE_FILE_CACHE);

            if ($this->configData[self::KEY_USE_FILE_CACHE] === true) {

                if (
                    !array_key_exists(self::KEY_FILE_CACHE_PATH, $this->configData)
                    || !is_string($this->configData[self::KEY_FILE_CACHE_PATH])
                ) {

                    $this->throwInvalidConfigDataException(self::KEY_FILE_CACHE_PATH);
                }

            }
        }

        return true;
    }

    /**
     * @param string $key
     *
     * @throws \KaiCMueller\Medium\Exception\ValidationException
     */
    protected function throwInvalidConfigDataException($key)
    {

        throw new ValidationException(sprintf('Configuration key "%s" invalid or missing', $key));
    }

    /**
     * @return int
     */
    public function getCacheTime()
    {

        return $this->configData[self::KEY_CACHE_TIME];
    }

    /**
     * @return string
     */
    public function getUser()
    {

        return $this->configData[self::KEY_USER];
    }

    /**
     * @return bool
     */
    public function getUseFileCache()
    {

        return $this->configData[self::KEY_USE_FILE_CACHE];
    }

    /**
     * @return string
     */
    public function getFileCachePath()
    {

        return $this->configData[self::KEY_FILE_CACHE_PATH];
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return $this->getBaseUrl() . self::CONFIG_URL_PATH;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return sprintf(self::CONFIG_BASE_URL, $this->getUser());
    }

}
