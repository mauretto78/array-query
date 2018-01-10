<?php
/**
 * This file is part of the ArrayQuery package.
 *
 * (c) Mauro Cassani<https://github.com/mauretto78>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayQuery\Helpers;

class ArrayHelper
{
    /**
     * @param $firstArray
     * @param $secondArray
     * @return bool
     */
    public static function checkIfTwoArraysAreConsistent($firstArray, $secondArray)
    {
        if (count(array_diff($firstArray, $secondArray)) > 0 || count(array_diff($secondArray, $firstArray)) > 0) {
            return false;
        }

        return true;
    }

    /**
     * @param $itemToCompare
     * @param $element
     * @return bool
     */
    public static function compareElementToItemKeyMap($itemToCompare, $element)
    {
        foreach ($itemToCompare as $key => $item){
            if(is_array($item) && ArrayHelper::isAnAssociativeArray($item)){
                if (false === ArrayHelper::checkIfTwoArraysAreConsistent(array_keys($item), array_keys($element[$key]))) {
                    return false;
                }

                return self::compareElementToItemKeyMap($item, $element[$key]);
            }
        }
    }

    /**
     * @param $arrayElement
     * @return array
     */
    public static function convertObjectToArray($arrayElement)
    {
        $convertedArray = [];

        foreach (self::convertToPlainArray($arrayElement) as $key => $element) {
            $key = explode("\\", $key);
            $key = end($key);
            $key = explode("\000", $key);

            $convertedArray[end($key)] = $element;
        }

        return $convertedArray;
    }

    /**
     * @param $array
     * @return mixed
     */
    public static function convertToObjectArray($array)
    {
        return json_decode(json_encode($array));
    }

    /**
     * @param $array
     * @return mixed
     */
    public static function convertToPlainArray($array)
    {
        return json_decode(json_encode($array), true);
    }

    /**
     * @param $key
     * @param $array
     * @return mixed
     */
    public static function getValueFromNestedArray(array $key, array $array)
    {
        array_shift($key);
        $value = $array[$key[0]];

        if(is_array($value)){
            return self::getValueFromNestedArray($key, $value);
        }

        return $value;
    }

    /**
     * @param array $array
     * @return bool
     */
    public static function isAnAssociativeArray(array $array)
    {
        if ([] === $array) return false;

        return array_keys($array) !== range(0, count($array) - 1);
    }
}
