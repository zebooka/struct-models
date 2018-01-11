<?php

namespace Zebooka\Struct\Collection;

use Zebooka\Struct\StructInterface;

interface StructCollectionInterface extends StructInterface, \Countable, \ArrayAccess, \IteratorAggregate
{
    /**
     * Construct item object using document data.
     * @param array|mixed $doc Usually this is an array, but can be scalar data.
     * @return StructInterface
     */
    public static function itemFromDoc($doc);

    /**
     * Check if supplied item is supported by collection.
     * @param StructInterface|mixed $item
     * @return bool
     */
    public static function consistsOf($item);
}
