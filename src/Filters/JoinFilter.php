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

class JoinFilter extends AbstractFilter
{
    /**
     * @param array $results
     * @param null $joinArray
     * @return array
     */
    public static function filter(array $results, $joinArray = null)
    {
        if ($joinArray) {
            foreach ($joinArray as $join) {
                foreach ($results as $key => $result) {
                    $arrayToJoin = $join['array'];
                    $arrayName = $join['arrayName'];
                    $parentKey = $join['parentKey'];
                    $foreignKey = $join['foreignKey'];

                    if (array_key_exists($parentKey, $result) && $arrayToJoin[$foreignKey] === $result[$parentKey]) {
                        $results[$key][$arrayName] = $arrayToJoin;
                    } else {
                        unset($results[$key]);
                    }
                }
            }
        }

        return $results;
    }
}
