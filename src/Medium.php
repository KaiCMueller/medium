<?php

namespace KaiCMueller\Medium;

use KaiCMueller\Medium\Cache\CacheInterface;
use KaiCMueller\Medium\Cache\FileCache;
use KaiCMueller\Medium\Exception\ClientException;

class Medium
{

    /**
     * @var \KaiCMueller\Medium\Cache\CacheInterface|null
     */
    protected $cache;

    /**
     * @var \KaiCMueller\Medium\Config
     */
    protected $config;

    /**
     * @var string|null
     */
    protected $feedData;

    /**
     * @var \KaiCMueller\Medium\Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $postCollection = [];

    public function returnBool($bool = true)
    {
        return $bool;
    }

    public function __construct(
        array $configData,
        CacheInterface $cache = null
    ) {

        $this->config = $this->createConfig($configData);

        if ($this->config->getUseFileCache()) {
            $cache = $this->createFileCache();
        }

        if ($cache !== null) {
            $this->cache = $this->createCache($cache);
        }

        $this->client = $this->createClient();
    }

    public function getPosts()
    {
        $rawFeedData = $this->build();
        $processedFeedData = $this->process($rawFeedData);
        $this->postCollection = $this->buildPostCollection($processedFeedData);

        return $this->postCollection;
    }

    protected function buildPostCollection($processedFeedData)
    {
        $collection = $this->createPostCollection();

        foreach ($processedFeedData as $postData) {
            $post = $this->createPost();
            $post->setData($postData);
            $collection->addPost($post);
        }

        return $collection;
    }

    /**
     * Get feed data from cache or remote
     */
    protected function build()
    {
        if (!$this->config->getUseFileCache() || $this->cache === null) {

            return $this->fetchData();
        }

        if ($this->cache->isOutdated()) {
            if ($data = $this->fetchData()) {
                $this->cache->write($data);
            }
        }

        return $this->cache->read();
    }

    /**
     * @return string|false
     */
    protected function fetchData()
    {
        try {

            return $this->client->fetch();
        } catch (ClientException $e) {

            return false;
        }
    }

    /**
     * @param string $rawFeedData
     * @return array
     */
    protected function process($rawFeedData)
    {
        $processor = $this->createProcessor();

        return $processor->process($rawFeedData);
    }

    /**
     * @param array $configData
     * @return \KaiCMueller\Medium\Config
     */
    protected function createConfig($configData)
    {

        return new Config($configData);
    }

    /**
     * @param \KaiCMueller\Medium\Cache\CacheInterface $cache
     * @return \KaiCMueller\Medium\Cache
     */
    protected function createCache(CacheInterface $cache)
    {

        return new Cache($cache, $this->config);
    }

    /**
     * @return \KaiCMueller\Medium\Client
     */
    protected function createClient()
    {

        return new Client($this->config);
    }

    /**
     * @return \KaiCMueller\Medium\Processor
     */
    protected function createProcessor()
    {

        return new Processor($this->config);
    }

    /**
     * @return \KaiCMueller\Medium\PostCollection
     */
    protected function createPostCollection()
    {

        return new PostCollection($this->config);
    }

    /**
     * @return \KaiCMueller\Medium\Post
     */
    protected function createPost()
    {

        return new Post($this->config);
    }

    /**
     * @return \KaiCMueller\Medium\Cache\FileCache
     */
    protected function createFileCache()
    {

        return new FileCache($this->config);
    }


}
