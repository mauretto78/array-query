<?php
/**
 * This file is part of the ArrayQuery package.
 *
 * (c) Mauro Cassani<https://github.com/mauretto78>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayQuery;

use ArrayQuery\Exceptions\EmptyArrayException;
use ArrayQuery\Exceptions\InvalidArrayException;
use ArrayQuery\Exceptions\NotValidCriterionOperatorException;
use ArrayQuery\Exceptions\NotValidKeyElementInArrayException;
use ArrayQuery\Exceptions\NotValidLimitsOfArrayException;
use ArrayQuery\Exceptions\NotValidSortingOperatorException;
use ArrayQuery\Filters\CriterionFilter;
use ArrayQuery\Filters\JoinFilter;
use ArrayQuery\Filters\SortingFilter;
use ArrayQuery\Filters\LimitFilter;

class QueryBuilder
{
    /**
     * @var array
     */
    private $criteria;

    /**
     * @var array
     */
    private $sortedBy;

    /**
     * @var array
     */
    private $limit;

    /**
     * @var array
     */
    private $join;

    /**
     * @var array
     */
    private $array;

    /**
     * QueryBuilder constructor.
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->setArray($array);
    }

    /**
     * @param array $array
     * @return static
     */
    public static function create(array $array)
    {
        return new static($array);
    }

    /**
     * @param $array
     *
     * @throws EmptyArrayException
     */
    private function setArray(array $array)
    {
        if (empty($array)) {
            throw new EmptyArrayException('Empty array provided.');
        }

        $this->array = $array;
    }

    /**
     * @param $key
     * @param $value
     * @param string $operator
     * @param null $dateFormat
     * @return $this
     * @throws NotValidCriterionOperatorException
     */
    public function addCriterion($key, $value, $operator = '=', $dateFormat = null)
    {
        if (!$this->isAValidCriterionOperator($operator)) {
            throw new NotValidCriterionOperatorException($operator.' is not a valid operator.');
        }

        $this->criteria[] = [
            'key' => $key,
            'value' => $value,
            'operator' => $operator,
            'date_format' => $dateFormat
        ];

        return $this;
    }

    /**
     * @param $operator
     * @return bool
     */
    private function isAValidCriterionOperator($operator)
    {
        return in_array($operator, array_keys(CriterionFilter::$operatorsMap));
    }

    /**
     * @param $key
     * @param string $operator
     *
     * @return $this
     *
     * @throws NotValidSortingOperatorException
     */
    public function sortedBy($key, $operator = 'ASC', $format = null)
    {
        if (!$this->isAValidSortingOperator($operator)) {
            throw new NotValidSortingOperatorException($operator.' is not a valid sorting operator.');
        }

        $this->sortedBy = [
            'key' => $key,
            'order' => $operator,
            'format' => $format
        ];

        return $this;
    }

    /**
     * @param $operator
     * @return bool
     */
    private function isAValidSortingOperator($operator)
    {
        return in_array($operator, SortingFilter::$operatorsMap);
    }

    /**
     * @param $offset
     * @param $length
     * @return $this
     * @throws NotValidLimitsOfArrayException
     */
    public function limit($offset, $length)
    {
        if (!is_integer($offset)) {
            throw new NotValidLimitsOfArrayException($offset.' must be an integer.');
        }

        if (!is_integer($length)) {
            throw new NotValidLimitsOfArrayException($length.' must be an integer.');
        }

        if ($offset > $length) {
            throw new NotValidLimitsOfArrayException($offset.' must be an < than '.$length.'.');
        }

        if ($length > count($this->array)) {
            throw new NotValidLimitsOfArrayException($length.' must be an > than array count.');
        }

        $this->limit = [
            'offset' => $offset,
            'lenght' => $length,
        ];

        return $this;
    }

    /**
     * @param $array
     * @param $arrayName
     * @param $parentKey
     * @param $foreignKey
     *
     * @return $this
     */
    public function join($array, $arrayName, $parentKey, $foreignKey)
    {
        $this->join[] = [
            'array' => $array,
            'arrayName' => $arrayName,
            'parentKey' => $parentKey,
            'foreignKey' => $foreignKey,
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        $results = $this->applySortingFilter($this->applyLimitFilter($this->applyCriteriaFilter($this->applyJoinFilter())));

        return array_map([$this, 'castElementToArray'], $results);
    }

    /**
     * @return array
     */
    public function getFirstResult()
    {
        return $this->getResults()[0] ?: [];
    }

    /**
     * @return array
     */
    public function getLastResult()
    {
        $count = count($this->getResults());

        return $this->getResults()[$count-1] ?: [];
    }

    /**
     * @return array
     */
    public function getShuffledResults()
    {
        $shuffledArray = [];
        $keys = array_keys($this->getResults());
        shuffle($keys);

        foreach ($keys as $key) {
            $shuffledArray[$key] = $this->getResults()[$key];
        }

        return $shuffledArray;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    private function applySortingFilter(array $array)
    {
        return SortingFilter::filter($array, $this->sortedBy);
    }

    /**
     * @param array $array
     *
     * @return array
     */
    private function applyLimitFilter(array $array)
    {
        return LimitFilter::filter($array, $this->limit);
    }

    /**
     * @return array
     */
    private function applyJoinFilter()
    {
        return JoinFilter::filter($this->array, $this->join);
    }

    /**
     * @return array
     */
    private function applyCriteriaFilter(array $array)
    {
        if (count($this->criteria) === 0) {
            return $array;
        }

        foreach ($this->criteria as $criterion) {
            $results = array_filter(
                (isset($results)) ? $results : $array, function ($element) use ($criterion) {
                    return CriterionFilter::filter($criterion, $element);
                }
            );

            $results = array_map(function($result) use ($criterion) {
                $key = explode(Constants::ALIAS_DELIMITER, $criterion['key']);
                if(count($key) > 1){
                    $oldkey = explode(Constants::ARRAY_SEPARATOR, $key[0]);
                    $newkey = $key[1];

                    $result = (array)($result);
                    $result[$newkey] = $result[$oldkey[0]];
                    unset($result[$oldkey[0]]);
                }
                return $result;
            }, $results);
        }

        return $results;
    }

    /**
     * @param $element
     * @return array
     */
    private function castElementToArray($element)
    {
        return (array) $element;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->getResults());
    }
}
