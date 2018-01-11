<?php

namespace Zebooka\Struct\Collection;

use Zebooka\Struct\StructTrait;

trait StructCollectionTrait
{
    use StructTrait;

    protected function assignProperties(array $data)
    {
        foreach ($data as $key => $value) {
            if ($this->isPropertyIsPublic($key)) {
                $this->{$key} = (static::consistsOf($value) ? $value : static::itemFromDoc($value));
            }
        }
        return $this;
    }

    public function __set($property, $value)
    {
        if (!static::consistsOf($value)) {
            throw new \UnexpectedValueException(
                sprintf(
                    'Undexpected item type supplied to %s collection. Can not set item "%s".',
                    get_called_class(),
                    $property
                )
            );
        }
        $this->{$property} = $value;
    }

    public function offsetExists($property)
    {
        return $this->isPropertyIsPublic($property) && null !== $this->{$property};
    }

    public function offsetGet($property)
    {
        return $this->isPropertyIsPublic($property) ? $this->{$property} : null;
    }

    public function offsetSet($property, $value)
    {
        if ($this->isPropertyIsPublic($property)) {
            $this->{$property} = $value;
        }
    }

    public function offsetUnset($property)
    {
        if ($this->isPropertyIsPublic($property)) {
            unset($this->{$property});
        }
    }

    public function count()
    {
        return count((new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC));
    }

    public function getIterator()
    {
        return new \ArrayIterator(
            array_map(
                function (\ReflectionProperty $reflectionProperty) {
                    return $reflectionProperty->getValue($this);
                },
                (new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC)
            )
        );
    }
}
