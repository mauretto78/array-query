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

use ArrayQuery\Constants;
use ArrayQuery\Filters\Criterion\FilterInterface;

class CriterionFilter extends AbstractFilter
{
    /**
     * @var array
     */
    public static $operatorsMap = [
        '=' => 'EqualsFilter',
        '>' => 'GreaterThanFilter',
        '>=' => 'GreaterThanEqualsFilter',
        '<' => 'LessThanFilter',
        '<=' => 'LessThanEqualsFilter',
        '!=' => 'NotEqualsFilter',
        'ARRAY_MATCH' => 'ArrayMatchFilter',
        'CONTAINS' => 'ContainsFilter',
        'ENDS_WITH' => 'EndsWithFilter',
        'EQUALS_DATE' => 'EqualsDateFilter',
        'GT_DATE' => 'GreaterThanDateFilter',
        'GTE_DATE' => 'GreaterThanEqualsDateFilter',
        'IN_ARRAY' => 'InArrayFilter',
        'IN_ARRAY_INVERSED' => 'InArrayInversedFilter',
        'LT_DATE' => 'LessThanDateFilter',
        'LTE_DATE' => 'LessThanEqualsDateFilter',
        'STARTS_WITH' => 'StartsWithFilter',
    ];

    /**
     * @param $criterion
     * @param $element
     * @return mixed
     */
    public static function filter($criterion, $element)
    {
        $key = explode(Constants::ALIAS_DELIMITER, $criterion['key']);
        $valueToCompare = $criterion['value'];
        $operator = $criterion['operator'];
        $dateFormat = $criterion['date_format'];

        $value = self::getArrayElementValueFromKey($key[0], $element);

        $filterClass = 'ArrayQuery\\Filters\\Criterion\\'.self::$operatorsMap[$operator];

        /** @var FilterInterface $filter */
        $filter = new $filterClass();

        return $filter->match($value, $valueToCompare, $dateFormat);
    }
}
