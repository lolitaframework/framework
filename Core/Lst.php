<?php

namespace lolita\LolitaFramework\Core;

use \ArrayAccess;
use \Iterator;
use \Countable;
use \Serializable;

class Lst implements ArrayAccess, Iterator, Countable, Serializable
{

    /**
     * Items container
     * @var array
     */
    protected $container = [];

    /**
     * Items count
     * @return int
     */
    public function count()
    {
        return count($this->container);
    }

    /**
     * Is me?
     * @param  mixed  $obj
     * @return boolean
     */
    public static function is($obj)
    {
        return $obj instanceof self;
    }

    /**
     * Get container
     * @return array
     */
    public function container()
    {
        return $this->container;
    }

    /**
     * Applies the callback to the elements of the given arrays
     * @param  callable $callback
     * @return Instance
     */
    public function map($callback)
    {
        $this->container = array_map($callback, (array) $this->container);
        return $this;
    }

    /**
     * Push new value
     * @param  mixed $val
     * @return Instance
     */
    public function push($val)
    {
        array_push($this->container, $val);
        return $this;
    }

    /**
     * Filters elements of an array using a callback function
     * @param  callable $callback
     * @return Instance
     */
    public function filter($callback)
    {
        $this->container = array_filter($this->container, $callback);
        return $this;
    }

    /**
     * Offset set [ArrayAccess]
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Offset exists [ArrayAccess]
     * @param  mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Offset unset [ArrayAccess]
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Offset get [ArrayAccess]
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Rewind the Iterator to the first element
     * @return void
     */
    public function rewind()
    {
        reset($this->container);
    }

    /**
     * Return the current element
     * @return mixed
     */
    public function current()
    {
        return current($this->container);
    }

    /**
     * Return the key of the current element
     * @return mixed
     */
    public function key()
    {
        return key($this->container);
    }

    /**
     * Move forward to next element
     * @return void
     */
    public function next()
    {
        next($this->container);
    }

    /**
     * Checks if current position is valid
     * @return void
     */
    public function valid()
    {
        return isset($this->container[ $this->key() ]);
    }

    /**
     * String representation of object
     * @return string
     */
    public function serialize()
    {
        return serialize($this->container);
    }

    /**
     * Constructs the object
     * @param  string
     * @return void
     */
    public function unserialize($data)
    {
        $this->container = unserialize($data);
    }
}
