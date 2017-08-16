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

use ArrayQuery\Exceptions\NotValidKeyElementInArrayException;

abstract class AbstractFilter
{
    const ARRAY_SEPARATOR = '.';

    /**
     * @param $key
     * @param $arrayElement
     * @return mixed
     */
    protected static function getArrayElementValueFromKey($key, $arrayElement)
    {
        return self::getValueFromKeysArray(
            explode(self::ARRAY_SEPARATOR, $key),
            (array) $arrayElement
        );
    }

    /**
     * @param $keysArray
     * @param $arrayElement
     * @return mixed
     * @throws NotValidKeyElementInArrayException
     */
    private static function getValueFromKeysArray($keysArray, $arrayElement)
    {
        if (count($keysArray)>1) {
            $key = array_shift($keysArray);

            return self::getValueFromKeysArray($keysArray, (array) $arrayElement[$key]);
        }

        if (!isset($arrayElement[$keysArray[0]])) {
            throw new NotValidKeyElementInArrayException($keysArray[0].' is not a valid key in the array.');
        }

        return $arrayElement[$keysArray[0]];
    }
}
