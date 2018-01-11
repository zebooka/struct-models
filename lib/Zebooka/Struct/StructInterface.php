<?php

namespace Zebooka\Struct;

interface StructInterface
{
    /**
     * Construct object using document data.
     * @param array|mixed $doc Usually this is an array, but can be scalar data.
     * @return StructInterface
     */
    public static function fromDoc($doc);

    /**
     * Map object into its document representation.
     * @return array|mixed
     */
    public function toDoc();
}
