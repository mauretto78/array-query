<?php

namespace ArrayQuery\Filters\Criterion;

interface FilterInterface
{
    /**
     * @param $value
     * @param $valueToCompare
     * @return mixed
     */
    public function match($value, $valueToCompare);
}
