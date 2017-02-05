<?php

namespace KaiCMueller\Medium;

class Post
{

    /**
     * @var \KaiCMueller\Medium\Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $data;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        if ($key === null) {

            return $this->data;
        }

        if (!array_key_exists($key, $this->data)) {

            return $default;
        }

        return $this->data[$key];
    }

    /**
     * @param mixed $default
     * @return string
     */
    public function getTitle($default = null)
    {
        return $this->get(Processor::POST_KEY_TITLE, $default);
    }

    /**
     * @param mixed $default
     * @return string
     */
    public function getText($default = null)
    {
        return $this->get(Processor::POST_KEY_TEXT, $default);
    }

    /**
     * @param mixed $default
     * @return string
     */
    public function getUrl($default = null)
    {
        return $this->get(Processor::POST_KEY_URL, $default);
    }

    /**
     * @param mixed $default
     * @return string
     */
    public function getDate($default = null)
    {
        return $this->get(Processor::POST_KEY_DATE, $default);
    }

    /**
     * @param mixed $default
     * @return array
     */
    public function getTags($default = null)
    {
        return $this->get(Processor::POST_KEY_TAGS, $default);
    }

}
