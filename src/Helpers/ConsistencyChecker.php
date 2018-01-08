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

class ConsistencyChecker
{
    /**
     * @param $element
     * @param $array
     * @return bool
     */
    public static function isElementValid($element, $array)
    {
        foreach ($array as $key => $item) {
            if (is_object($item)) {
                $array[$key] = ArrayConverter::convertToPlainArray($item);
            }
        }

        if (is_object($element)) {
            $element = ArrayConverter::convertToPlainArray($element);
        }

        $FirstItemKeyMap = array_keys(current($array));
        $ItemKeyMap = array_keys($element);

        if (count(array_diff($FirstItemKeyMap, $ItemKeyMap)) > 0 || count(array_diff($ItemKeyMap, $FirstItemKeyMap)) > 0) {
            return false;
        }

        return true;
    }

    /**
     * @param $array
     * @return bool
     */
    public static function isValid($array)
    {
        foreach ($array as $element) {
            if (false === self::isElementValid($element, $array)) {
                return false;
            }
        }

        return true;
    }
}
