<?php

namespace Zebooka\Struct\Collection;

use Zebooka\Struct\SimpleStructTrait;

/**
 * Use this trait if your collection model constructed without params.
 */
trait SimpleStructCollectionTrait
{
    use StructCollectionTrait;

    public static function fromDoc($doc)
    {
        $struct = new static();
        $struct->assignProperties($doc);
        return $struct;
    }
}
