<?php

namespace KaiCMueller\Medium;

use KaiCMueller\Medium\Exception\ClientException;

class Client
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var \KaiCMueller\Medium\Config
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->httpClient = new \GuzzleHttp\Client();

        $this->config = $config;
    }

    /**
     * @return string
     * @throws \KaiCMueller\Medium\Exception\ClientException
     */
    public function fetch()
    {
        try {
            $response = $this->httpClient->request('GET', $this->config->getFeedUrl());
        } catch (\Exception $e) {

            throw new ClientException(sprintf("Can not read from URL: %s", $this->config->getFeedUrl()));
        }

        return $response->getBody()->getContents();
    }

}
