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

class EndsWithFilter implements FilterInterface
{
    /**
     * @param $value
     * @param $valueToCompare
     * @return bool
     */
    public function match($value, $valueToCompare, $dateFormat = null)
    {
        $valueToCompareLenght = strlen($valueToCompare);
        $valueLenght = strlen($value);
        $valueFoot = substr($value, ($valueLenght-$valueToCompareLenght), $valueToCompareLenght);

        return $valueToCompare === $valueFoot;
    }
}
