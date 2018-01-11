<?php

namespace Zebooka\Struct;

use PHPUnit\Framework\TestCase;

class StructTest extends TestCase
{
    public function test_fromDoc()
    {
        $struct = PersonExample::fromDoc(['name' => 'John Doe', 'birthday' => '1985-07-15']);
        $this->assertObjectHasAttribute('name', $struct);
        $this->assertObjectHasAttribute('birthday', $struct);
        $this->assertInstanceOf('DateTime', $struct->birthday);
        $this->assertEquals('1985-07-15', $struct->birthday->format('Y-m-d'));
        $this->assertNull($struct->unexistingProperty);
    }

    public function test_fromDoc_without_birthday()
    {
        $struct = PersonExample::fromDoc(['name' => 'John Doe']);
        $this->assertObjectHasAttribute('name', $struct);
        $this->assertObjectNotHasAttribute('birthday', $struct);
    }

    public function test_fromDoc_with_invalid_birthday()
    {
        $struct = PersonExample::fromDoc(['name' => 'John Doe', 'birthday' => ['1985', '07', '15']]);
        $this->assertObjectHasAttribute('name', $struct);
        $this->assertObjectHasAttribute('birthday', $struct);
        $this->assertInternalType('array', $struct->birthday);
    }


    public function test_toDoc()
    {
        $original = ['name' => 'John Doe', 'birthday' => 'Mon, 15 Jul 1985 00:00:00 +0800'];
        $struct = PersonExample::fromDoc($original);
        $doc = $struct->toDoc();
        $this->assertInternalType('array', $doc);
        $this->assertEquals(['name', 'birthday'], array_keys($doc));
        $this->assertInternalType('string', $doc['name']);
        $this->assertInternalType('string', $doc['birthday']);
    }
}

/**
 * @property string $name
 * @property \DateTime $birthday
 */
class PersonExample implements StructInterface
{
    use StructTrait;

    public static function fromDoc($doc, $struct = null)
    {
        $person = new self();
        $person->assignProperties($doc);
        if (is_scalar($person->birthday)) {
            // do not harm document if value is not of expected type
            $person->birthday = new DateTimeExample($person->birthday);
        }
        return $person;
    }
}

class DateTimeExample extends \DateTime implements StructInterface
{
    public static function fromDoc($doc)
    {
        return new self($doc);
    }

    public function toDoc()
    {
        return $this->format(self::RFC2822);
    }
}
