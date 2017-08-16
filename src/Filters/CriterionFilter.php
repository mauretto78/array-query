<?php

namespace ArrayQuery\Filters;

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
        'ARRAY' => 'ArrayFilter',
        'ARRAY_INVERSED' => 'ArrayInversedFilter',
        'CONTAINS' => 'ContainsFilter',
    ];

    /**
     * @param $criterion
     * @param $element
     * @return mixed
     */
    public static function filter($criterion, $element)
    {
        $value = self::getArrayElementValueFromKey($criterion['key'], $element);
        $valueToCompare = $criterion['value'];
        $filterClass = 'ArrayQuery\\Filters\\Criterion\\'.self::$operatorsMap[$criterion['operator']];

        /** @var FilterInterface $filter */
        $filter = new $filterClass();

        return $filter->match($value, $valueToCompare);
    }
}
