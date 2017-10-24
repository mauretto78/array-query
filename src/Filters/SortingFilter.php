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

class SortingFilter extends AbstractFilter
{
    /**
     * @var array
     */
    public static $operatorsMap = [
        'ASC',
        'DESC',
        'DATE_ASC',
        'DATE_DESC',
    ];

    /**
     * @param array $results
     * @param null $sortingArray
     * @return array
     */
    public static function filter(array $results, $sortingArray = null)
    {
        if (is_array($sortingArray) && count($sortingArray)) {
            return self::sort($results, $sortingArray);
        }

        return $results;
    }

    /**
     * @param $results
     * @param $sortingArray
     * @return array
     */
    private static function sort($results, $sortingArray)
    {
        usort($results, function ($first, $second) use ($sortingArray) {
            $valueA = self::getArrayElementValueFromKey($sortingArray['key'], $first);
            $valueB = self::getArrayElementValueFromKey($sortingArray['key'], $second);

            if (isset($sortingArray['format'])) {
                $valueA = \DateTimeImmutable::createFromFormat(($sortingArray['format']) ?: 'Y-m-d', $valueA);
                $valueB = \DateTimeImmutable::createFromFormat(($sortingArray['format']) ?: 'Y-m-d', $valueB);
            }

            if ($valueA == $valueB) {
                return 0;
            }

            return ($valueA < $valueB) ? -1 : 1;
        });

        if ($sortingArray['order'] === 'DESC' || $sortingArray['order'] === 'DATE_DESC') {
            return array_reverse($results);
        }

        return $results;
    }
}
