<?php

namespace Zebooka\Struct;

/**
 * Use this trait if your model does not contain sub models.
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
