<?php

use KaiCMueller\Medium\Medium;

class MediumTest extends PHPUnit_Framework_TestCase
{

    /**
     * Initial integration test
     */
    public function testInit()
    {

        $config = [
            'user' => '@kcmueller',
        ];

        $medium = new Medium($config);

        $posts = $medium->getPosts();

        $this->assertInstanceOf('KaiCMueller\Medium\PostCollection', $posts);
    }

}
