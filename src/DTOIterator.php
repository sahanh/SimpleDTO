<?php

declare(strict_types=1);

namespace SH\SimpleDTO;

use Iterator;

class DTOIterator implements Iterator
{
    /**
     * The key of the current element.
     *
     * @var mixed
     */
    protected $position = 0;

    /**
     * The instance of the DTO.
     *
     * @var \SH\SimpleDTO\DTO|bool
     */
    protected $dto = false;

    /**
     * The data from the DTO instance.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Create new DTOIterator instance.
     *
     * @param \SH\SimpleDTO\DTO $dto
     *
     * @return void
     */
    public function __construct(DTO $dto)
    {
        $this->dto = $dto;
        $this->data = $dto->getData();
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void
     */
    public function rewind(): void
    {
        reset($this->data);

        $this->position = key($this->data);
    }

    /**
     * Return the current element.
     *
     * @return mixed
     */
    public function current(): mixed
    {
        return $this->dto->get($this->position);
    }

    /**
     * Return the key of the current element.
     *
     * @return mixed
     */
    public function key(): mixed
    {
        return $this->position;
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next(): void
    {
        next($this->data);
        $key = key($this->data);

        $this->position = null === $key ? false : $key;
    }

    /**
     * Checks if current position is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        if (false === $this->position) {
            return false;
        }

        return $this->dto->offsetExists($this->position);
    }
}
