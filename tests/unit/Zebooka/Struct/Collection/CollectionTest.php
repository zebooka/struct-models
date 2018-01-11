<?php

namespace Zebooka\Struct;

use PHPUnit\Framework\TestCase;
use Zebooka\Struct\Collection\SimpleStructCollectionTrait;
use Zebooka\Struct\Collection\StructCollectionInterface;

class CollectionTest extends TestCase
{
    public function test_fromDoc()
    {
        $collection = FruitsCollectionExample::fromDoc([['taste' => 'sweet'], ['taste' => 'sour']]);

        $this->assertInstanceOf('\ArrayAccess', $collection);
        $this->assertInstanceOf('\Countable', $collection);

        $this->assertArrayHasKey(0, $collection);
        $this->assertArrayHasKey(1, $collection);
        $this->assertCount(2, $collection);
        $collection->{2} = FruitExample::fromDoc(['taste' => 'bitter']);
        $collection[3] = FruitExample::fromDoc(['taste' => 'plain']);
        $this->assertCount(4, $collection);
        $this->assertInstanceOf('\\Zebooka\\Struct\\FruitExample', $collection[3]);
        unset($collection[3]);
        $this->assertCount(3, $collection);

        $this->assertInstanceOf('\Traversable', $collection);
        foreach ($collection as $item) {
            $this->assertInstanceOf('\\Zebooka\\Struct\\FruitExample', $item);
        }
    }

    public function test_create_empty_collection()
    {
        $collection = FruitsCollectionExample::fromDoc([]);
        $this->assertEmpty($collection);
        foreach ($collection as $item) {
            $this->fail('Collection is not empty.');
        }
        unset($collection[123]);
    }

    public function test_assign_invalid_property()
    {
        $collection = FruitsCollectionExample::fromDoc([]);
        $this->expectException('\\UnexpectedValueException');
        $this->expectExceptionMessage('Undexpected item type supplied to');
        $collection->test = 123;
    }

    public function test_array_access_set_invalid_property()
    {
        $collection = FruitsCollectionExample::fromDoc([]);
        $this->expectException('\\UnexpectedValueException');
        $this->expectExceptionMessage('Undexpected item type supplied to');
        $collection['test'] = 123;
    }

    public function test_toDoc()
    {
        $original = [['taste' => 'sweet'], ['taste' => 'sour']];
        $collection = FruitsCollectionExample::fromDoc($original);
        $this->assertEquals($original, $collection->toDoc());
    }
}

class FruitsCollectionExample implements StructCollectionInterface
{
    use SimpleStructCollectionTrait;

    public static function itemFromDoc($doc)
    {
        return FruitExample::fromDoc($doc);
    }

    public static function consistsOf($item)
    {
        return $item instanceof FruitExample;
    }
}

/**
 * @property float $taste
 */
class FruitExample implements StructInterface
{
    use SimpleStructTrait;
}

