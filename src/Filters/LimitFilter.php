<?php
/**
 * This file is part of the ArrayQuery package.
 *
 * (c) Mauro Cassani<https://github.com/mauretto78>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
