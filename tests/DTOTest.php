<?php

namespace SH\SimpleDTO\Tests;

use RuntimeException;
use SH\SimpleDTO\DTO;
use PHPUnit\Framework\TestCase;

class DTOTest extends TestCase
{
    protected $dto;
    protected $data;

    public function setUp(): void
    {
        $this->data = require 'dataset.php';
        $this->dto = DTO::make(json_decode($this->data, true));
    }

    public function testGet()
    {
        $this->assertInstanceOf('SH\SimpleDTO\DTO', $this->dto->get('name'));
        $this->assertSame('cake', $this->dto->get('name.slug'));
        $this->assertSame('Sugar', $this->dto->get('topping.2.type'));

        $this->assertSame('cake', $this->dto->name->slug);
        $this->assertSame('Sugar', $this->dto->topping[2]->type);
    }

    public function testNestedDTO()
    {
        $this->assertInstanceOf('SH\SimpleDTO\DTO', $this->dto->get('topping'));
        $this->assertInstanceOf('SH\SimpleDTO\DTO', $this->dto->get('topping.0'));
    }

    public function testArrayAccess()
    {
        $d = $this->dto;
        $this->assertSame('cake', $d['name']['slug']);
        $this->assertSame('Sugar', $d['topping'][2]['type']);
    }

    public function testNonExistingItems()
    {
        $this->assertNull($this->dto->get('not_in.slug.but_nested'));
    }

    /**
     * if an array, should be able to iterate.
     */
    public function testArrayIterating()
    {
        $compare = ['slug', 'cake', 'label', 'Cake'];

        $check = [];
        foreach ($this->dto['name'] as $key => $value) {
            $check[] = $key;
            $check[] = $value;
        }

        $this->assertEquals(0, count(array_diff($compare, $check)));
    }

    /**
     * if an array, should be able to iterate.
     */
    public function testGetArrayIterating()
    {
        $compare = ['slug', 'cake', 'label', 'Cake'];

        $check = [];
        foreach ($this->dto->get('name') as $key => $value) {
            $check[] = $key;
            $check[] = $value;
        }

        $this->assertEquals(0, count(array_diff($compare, $check)));
    }

    public function testJsonSerialize()
    {
        $this->assertSame($this->data, json_encode($this->dto));
    }

    public function testDataModifyThroughSet()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Data cannot be modified');

        $this->dto->name = 's';
    }

    public function testDataModifyThroughArrayAccess()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Data cannot be modified');

        $this->dto['name'] = 's';
    }

    public function testCount()
    {
        $this->assertEquals(6, count($this->dto));
    }

    public function testStdClass()
    {
        $data = $this->data;
        $d2 = DTO::make(json_decode($data));

        $this->assertSame($this->dto->getData(), $d2->getData());
    }

    public function testNestedArrayLoopsWithDTOObjects()
    {
        $arr = DTO::make(['first' => ['name' => 'Sahan'], 'second']);

        $return = [];

        foreach ($arr as $index => $value) {
            $return[$index] = $value;
        }

        $this->assertInstanceOf(DTO::class, $return['first']);
        $this->assertSame('Sahan', $return['first']->name);
        $this->assertSame('second', $return[0]);
    }
}
