<?php
/**
 * This file is part of the ArrayQuery package.
 *
 * (c) Mauro Cassani<https://github.com/mauretto78>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayQuery\Filters\Criterion;

use ArrayQuery\Helpers\ArrayHelper;

class InArrayFilter implements FilterInterface
{
    /**
     * @param $value
     * @param $valueToCompare
     * @return bool
     */
    public function match($value, $valueToCompare, $dateFormat = null)
    {
        return in_array($value, ArrayHelper::convertToPlainArray($valueToCompare));
    }
}
