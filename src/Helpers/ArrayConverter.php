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

class ArrayConverter
{
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
}
