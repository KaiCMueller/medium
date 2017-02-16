<?php

namespace KaiCMueller\Medium;

use KaiCMueller\Medium\Exception\ProcessingException;

class Processor
{

    const POST_KEY_TITLE = 'title';
    const POST_KEY_TEXT = 'text';
    const POST_KEY_URL = 'url';
    const POST_KEY_DATE = 'date';
    const POST_KEY_TAGS = 'tags';

    const DATA_KEY_PAYLOAD = 'payload';
    const DATA_KEY_PAYLOAD_REFERENCES = 'references';
    const DATA_KEY_PAYLOAD_REFERENCES_POST = 'Post';
    const DATA_KEY_PAYLOAD_REFERENCES_POST_TITLE = 'title';
    const DATA_KEY_PAYLOAD_REFERENCES_POST_FIRSTPUBLISHEDAT = 'firstPublishedAt';
    const DATA_KEY_PAYLOAD_REFERENCES_POST_CONTENT = 'content';
    const DATA_KEY_PAYLOAD_REFERENCES_POST_CONTENT_SUBTITLE = 'subtitle';
    const DATA_KEY_PAYLOAD_REFERENCES_POST_UNIQUESLUG = 'uniqueSlug';
    const DATA_KEY_PAYLOAD_REFERENCES_POST_VIRTUALS = 'virtuals';
    const DATA_KEY_PAYLOAD_REFERENCES_POST_VIRTUALS_TAGS = 'tags';
    const DATA_KEY_PAYLOAD_REFERENCES_POST_VIRTUALS_TAGS_NAME = 'name';

    /**
     * @var \KaiCMueller\Medium\Config
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $rawData
     * @return array
     *
     * @throws \KaiCMueller\Medium\Exception\ProcessingException
     */
    public function process($rawData)
    {
        // find beginning of valid json data
        $rawData = strstr($rawData, '{');
        if (!$rawData) {

            throw new ProcessingException("Unexpected data");
        }

        $data = $this->decode($rawData);
        if (json_last_error() !== 0) {

            throw new ProcessingException("Invalid JSON data");
        }

        if (!isset($data[self::DATA_KEY_PAYLOAD]['references']['Post'])) {

            throw new ProcessingException("Unexpected data");
        }

        $data = $this->getArrayData(
            [
                self::DATA_KEY_PAYLOAD,
                self::DATA_KEY_PAYLOAD_REFERENCES,
                self::DATA_KEY_PAYLOAD_REFERENCES_POST
            ],
            $data
        );

        $posts = [];

        foreach ($data as $post) {

            $tags = [];

            if (
                $tagData = $this->getArrayData(
                    [
                        self::DATA_KEY_PAYLOAD_REFERENCES_POST_VIRTUALS,
                        self::DATA_KEY_PAYLOAD_REFERENCES_POST_VIRTUALS_TAGS
                    ],
                    $post,
                    false
                )
            ) {
                if (is_array($tagData)) {
                    foreach ($tagData as $tag) {
                        if (
                            $tagName = $this->getArrayData(
                                [
                                    self::DATA_KEY_PAYLOAD_REFERENCES_POST_VIRTUALS_TAGS_NAME
                                ],
                                $tag,
                                false,
                                false
                            )
                        ) {
                            $tags[] = $tagName;
                        }
                    }
                }
            }

            $posts[] = [
                self::POST_KEY_TITLE =>
                    $this->getArrayData(
                        [
                            self::DATA_KEY_PAYLOAD_REFERENCES_POST_TITLE
                        ],
                        $post
                    ),
                self::POST_KEY_TEXT =>
                    $this->getArrayData(
                        [
                            self::DATA_KEY_PAYLOAD_REFERENCES_POST_CONTENT,
                            self::DATA_KEY_PAYLOAD_REFERENCES_POST_CONTENT_SUBTITLE
                        ],
                        $post
                    ),
                self::POST_KEY_URL =>
                    $this->config->getBaseUrl()
                    . $this->getArrayData(
                        [
                            self::DATA_KEY_PAYLOAD_REFERENCES_POST_UNIQUESLUG
                        ],
                        $post
                    ),
                self::POST_KEY_DATE =>
                    $this->microtimeToDate(
                        $this->getArrayData(
                            [
                                self::DATA_KEY_PAYLOAD_REFERENCES_POST_FIRSTPUBLISHEDAT,
                            ],
                            $post
                        )
                    ),
                self::POST_KEY_TAGS => $tags
            ];

        }

        return $posts;
    }

    /**
     * @param array $keys
     * @param array $array
     * @param bool $required
     * @param mixed $default
     * @return null
     *
     * @throws \KaiCMueller\Medium\Exception\ProcessingException
     */
    protected function getArrayData(array $keys, $array, $required = true, $default = null)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                if ($required) {

                    throw new ProcessingException(sprintf("Post data not found: %s", implode('=>', $keys)));
                }

                return $default;
            }

            $array = $array[$key];
        }

        return $array;
    }

    /**
     * @param $rawData
     * @return array
     *
     * @throws \KaiCMueller\Medium\Exception\ProcessingException
     */
    protected function decode($rawData)
    {
        $decodedData = json_decode($rawData, true);

        return $decodedData;
    }

    /**
     * @param int $microtime
     * @return \DateTime
     */
    protected function microtimeToDate($microtime)
    {
        return \DateTime::createFromFormat('U', intval($microtime / 1000));
    }

}
