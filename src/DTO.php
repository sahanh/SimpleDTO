<?php

declare(strict_types=1);

namespace SH\SimpleDTO;

use stdClass;
use Countable;
use ArrayAccess;
use JsonSerializable;
use RuntimeException;
use IteratorAggregate;
use Illuminate\Support\Arr;

class DTO implements JsonSerializable, ArrayAccess, IteratorAggregate, Countable
{
    /**
     * The collection of data.
     *
     * @var array
     */
    protected array $data;

    /**
     * Create new DTO instance.
     *
     * @param array $data
     *
     * @return void
     */
    protected function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function __get($attr)
    {
        return $this->get($attr);
    }

    /**
     * {@inheritDoc}
     */
    public function __set($attribute, $value)
    {
        throw new RuntimeException('Data cannot be modified');
    }

    /**
     * Build a DTO object.
     *
     * @param \stdClass|array $data
     *
     * @return \SH\SimpleDTO\DTO
     */
    public static function make(stdClass | array $data): DTO
    {
        $arr = json_decode(json_encode($data), true);

        return new self($arr);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(mixed $key = null, mixed $default = null): mixed
    {
        $data = Arr::get($this->data, $key, $default);

        if (is_array($data)) {
            return new self($data);
        }

        return $data;
    }

    /**
     * Get all the data in this data object.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @interface ArrayAccess
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Get an item at a given offset.
     *
     * @interface ArrayAccess
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set the item at a given offset.
     *
     * @interface ArrayAccess
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        throw new RuntimeException('Data cannot be modified');
    }

    /**
     * Unset the item at a given offset.
     *
     * @interface ArrayAccess
     *
     * @param mixed $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        throw new RuntimeException('Data cannot be modified');
    }

    /**
     * convert data to json.
     *
     * @interface JsonSerializable
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    /**
     * Count the number of data elements in this object.
     *
     * @param int $mode
     *
     * @return int
     */
    public function count($mode = \COUNT_NORMAL)
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
