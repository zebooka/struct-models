<?php

namespace Zebooka\Struct;

use PHPUnit\Framework\TestCase;

class StructPrivacyTest extends TestCase
{
    public function test_property_assignment_is_secure()
    {
        $privacy = PrivacyExample::fromDoc(['protected' => 'not', 'private' => 'not']);
        $reflection = new \ReflectionObject($privacy);
        $protected = $reflection->getProperty('protected');
        $this->assertTrue($protected->isProtected());
        $protected->setAccessible(true);
        $this->assertNotEquals('not', $protected->getValue($privacy));
        $this->assertEquals('protected', $protected->getValue($privacy));

        $private = $reflection->getProperty('private');
        $this->assertTrue($private->isPrivate());
        $private->setAccessible(true);
        $this->assertNotEquals('not', $private->getValue($privacy));
        $this->assertEquals('private', $private->getValue($privacy));
    }
}

class PrivacyExample implements StructInterface
{
    use StructTrait;

    protected $protected;
    private $private;

    public function __construct($protected, $private)
    {
        $this->protected = $protected;
        $this->private = $private;
    }

    public static function fromDoc($doc)
    {
        $self = new self('protected', 'private');
        $self->assignProperties($doc);
        return $self;
    }
}
