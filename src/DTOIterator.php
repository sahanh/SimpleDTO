<?php
namespace SH\SimpleDTO;

use Iterator;

class DTOIterator implements Iterator
{
    private $position = 0;
    private $dto      = false;
    private $data     = [];

    public function __construct(DTO $dto)
    {
        $this->dto  = $dto;
        $this->data = $dto->getData();
    }

    public function rewind()
    {
        reset($this->data);
        $this->position = key($this->data);
    }

    public function current()
    {
        return $this->dto->get($this->position);
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        next($this->data);
        $key = key($this->data);

        $this->position = $key === null ? false : $key;
    }

    public function valid()
    {
        if ($this->position === false)
            return false;
        else
            return $this->dto->offsetExists($this->position);
    }
}