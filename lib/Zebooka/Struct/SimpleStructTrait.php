<?php

namespace Zebooka\Struct;

/**
 * Use this trait if your model does not contain sub models and you can construct it without params.
 */
trait SimpleStructTrait
{
    use StructTrait;

    public static function fromDoc($doc)
    {
        $struct = new static();
        $struct->assignProperties($doc);
        return $struct;
    }
}
