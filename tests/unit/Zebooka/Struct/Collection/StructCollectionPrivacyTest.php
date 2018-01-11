<?php

namespace Zebooka\Struct\Collection;

use PHPUnit\Framework\TestCase;
use Zebooka\Struct\SimpleStructTrait;
use Zebooka\Struct\StructInterface;

class StructCollectionPrivacyTest extends TestCase
{
    public function test_property_assignment_is_secure()
    {
        $collection = PrivacyCollectionExample::fromDoc(['private' => ['not' => true], 'protected' => ['not' => true]]);

        $this->assertNull($collection['protected']);
        $reflection = new \ReflectionObject($collection);
        $protected = $reflection->getProperty('protected');
        $this->assertTrue($protected->isProtected());
        $protected->setAccessible(true);
        $this->assertNotInstanceOf('Zebooka\\Struct\\Collection\\ItemExample', $protected->getValue($collection));
        $this->assertEquals('protected', $protected->getValue($collection));

        $this->assertNull($collection['private']);
        $reflection = new \ReflectionObject($collection);
        $private = $reflection->getProperty('private');
        $this->assertTrue($private->isprivate());
        $private->setAccessible(true);
        $this->assertNotInstanceOf('Zebooka\\Struct\\Collection\\ItemExample', $private->getValue($collection));
        $this->assertEquals('private', $private->getValue($collection));
    }
}

class PrivacyCollectionExample implements StructCollectionInterface
{
    use SimpleStructCollectionTrait;

    protected $protected = 'protected';
    private $private = 'private';

    public static function consistsOf($item)
    {
        return $item instanceof ItemExample;
    }

    public static function itemFromDoc($doc)
    {
        return ItemExample::fromDoc($doc);
    }
}

class ItemExample implements StructInterface
{
    use SimpleStructTrait;
}
