<?php

namespace KaiCMueller\Medium;

class PostCollection implements \ArrayAccess, \Iterator
{

    /**
     * @var array
     */
    protected $container = [];

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @param \KaiCMueller\Medium\Post $post
     * @return $this
     */
    public function addPost(Post $post)
    {
        $this->offsetSet(null, $post);

        return $this;
    }

    /**
     * @return array
     */
    public function getPosts()
    {
        return $this->container;
    }

    ###############################################
    # Array Access and Iterator Implementation
    ###############################################

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {

        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {

        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {

        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    function rewind()
    {
        $this->position = 0;
    }

    function current()
    {
        return $this->container[$this->position];
    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        ++$this->position;
    }

    function valid()
    {
        return isset($this->container[$this->position]);
    }
}
