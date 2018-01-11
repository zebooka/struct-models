<?php

namespace Zebooka\Struct;

trait StructTrait
{
    public function __get($property)
    {
        return null;
    }

    protected function assignProperties(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    public function toDoc()
    {
        return $this->arrayToDocRecursive((array)$this);
    }

    private function arrayToDocRecursive(array $data, $tree = [])
    {
        $doc = [];
        foreach ($data as $key => $value) {
            if (!strlen($key) || $key[0] === "\0") {
                // skip properties that do not have name or begin with NUL-byte (private/proptected)
                continue;
            }

            if (is_scalar($value)) {
                $doc[$key] = $value;
            } elseif (is_array($value)) {
                $doc[$key] = $this->arrayToDocRecursive($value, array_merge($tree, [$key]));
            } elseif ($value instanceof StructInterface) {
                $doc[$key] = $value->toDoc();
            } else {
                throw new \UnexpectedValueException(
                    sprintf(
                        '%s struct contains public property %s that is not scalar, array or %s.',
                        get_class($this),
                        implode('->', array_merge($tree, [$key])),
                        'StructInterface'
                    )
                );
            }
        }
        return $doc;
    }

    protected function isPropertyIsPublic($property)
    {
        try {
            return (new \ReflectionProperty($this, $property))->isPublic();
        } catch (\ReflectionException $e) {
            // object has no such property, thus it is considered public
            return true;
        }
    }
}
