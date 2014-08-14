<?php
use SH\SimpleDTO\DTO;

class DTOTest extends PHPUnit_Framework_Testcase
{
    protected $dto;
    protected $data;

    public function setUp()
    {
        $this->data = require 'dataset.php';
        $this->dto  = DTO::make(json_decode($this->data, true));
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
     * if an array, should be able to iterate
     */
    public function testArrayIterating()
    {
        $compare = ['slug', 'cake', 'label', 'Cake'];

        $check   = [];
        foreach ($this->dto['name'] as $key => $value) {
            $check[] = $key;
            $check[] = $value;
        }

        $this->assertEquals(0, count(array_diff($compare, $check)));
    }

    /**
     * if an array, should be able to iterate
     */
    public function testGetArrayIterating()
    {
        $compare = ['slug', 'cake', 'label', 'Cake'];

        $check   = [];
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

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Data cannot be modified
     */
    public function testDataModifyThroughSet()
    {
        $this->dto->name = 's';
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Data cannot be modified
     */
    public function testDataModifyThroughArrayAccess()
    {
        $this->dto['name'] = 's';
    }

    public function testStdClass()
    {
        $data = $this->data;
        $d2   = DTO::make(json_decode($data));
        $this->assertSame($this->dto->getData(), $d2->getData());
    }
}