<?php

namespace ArrayQuery\Filters\Criterion;

class ArrayFilter implements FilterInterface
{
    /**
     * @param $value
     * @param $valueToCompare
     * @return bool
     */
    public function match($value, $valueToCompare)
    {
        return in_array($value, (array) $valueToCompare);
    }
}
