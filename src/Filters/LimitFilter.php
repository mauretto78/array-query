<?php

namespace ArrayQuery\Filters;

class LimitFilter extends AbstractFilter
{
    /**
     * @param array $results
     * @param null $limitArray
     * @return array
     */
    public static function filter(array $results, $limitArray = null)
    {
        if (is_array($limitArray) && count($limitArray)) {
            return self::slice($results, $limitArray);
        }

        return $results;
    }

    /**
     * @param $results
     * @param $limitArray
     * @return array
     */
    private static function slice($results, $limitArray)
    {
        return array_slice($results, $limitArray['offset'], $limitArray['lenght']);
    }
}
