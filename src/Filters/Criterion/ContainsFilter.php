<?php

namespace ArrayQuery\Filters\Criterion;

class ContainsFilter implements FilterInterface
{
    /**
     * @param $value
     * @param $valueToCompare
     * @return bool
     */
    public function match($value, $valueToCompare)
    {
        return stripos($value, $valueToCompare) !== false;
    }
}
