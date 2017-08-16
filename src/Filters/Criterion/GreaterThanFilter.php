<?php

namespace ArrayQuery\Filters\Criterion;

class GreaterThanFilter implements FilterInterface
{
    /**
     * @param $value
     * @param $valueToCompare
     * @return bool
     */
    public function match($value, $valueToCompare)
    {
        return $value > $valueToCompare;
    }
}
