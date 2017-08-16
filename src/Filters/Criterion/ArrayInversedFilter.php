<?php

namespace ArrayQuery\Filters\Criterion;

class ArrayInversedFilter implements FilterInterface
{
    /**
     * @param $value
     * @param $valueToCompare
     * @return bool
     */
    public function match($value, $valueToCompare)
    {
        return in_array($valueToCompare, (array) $value);
    }
}
