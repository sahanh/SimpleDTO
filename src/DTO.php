<?php
namespace SH\SimpleDTO;

use RuntimeException;
use JsonSerializable;
use ArrayAccess;
use IteratorAggregate;
use ArrayIterator;
use Countable;

class DTO implements JsonSerializable, ArrayAccess, IteratorAggregate, Countable
{
    protected $data;

    /**
     * factory method to build a DTO
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function make($data)
    {
        $arr = json_decode(json_encode($data), true);
        return new static($arr);
    }

    protected function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function __get($attr)
    {
        return $this->get($attr);
    }

    public function __set($attribute, $value)
    {
        throw new RuntimeException('Data cannot be modified');
    }

    public function get($key, $default = null)
    {
        $data = array_get($this->data, $key, $default);
        
        if (is_array($data))
            return new static($data);
        else
            return $data;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * Determine if an item exists at an offset.
     * @interface ArrayAccess
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Get an item at a given offset.
     * @interface ArrayAccess
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set the item at a given offset.
     * @interface ArrayAccess
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        throw new RuntimeException('Data cannot be modified');
    }

    /**
     * Unset the item at a given offset.
     * @interface ArrayAccess
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        throw new RuntimeException('Data cannot be modified');
    }

    /**
     * convert data to json
     * @interface JsonSerializable
     * @return [type] [description]
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    public function count($mode = COUNT_NORMAL)
    {
        return count($this->data);
    }

    /**
     * @interface IteratorAggregate
     */
    public function getIterator()
    {
        return new DTOIterator($this);
    }
}