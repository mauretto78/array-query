<?php

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
