<?php

namespace ArrayQuery\Tests;

use PHPUnit\Framework\TestCase;
use ArrayQuery\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    /**
     * @var array
     */
    private $usersArrays;

    /**
     * setup configuration.
     */
    public function setUp()
    {
        $this->usersArrays = require __DIR__.'/bootstrap.php';
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\EmptyArrayException
     * @expectedExceptionMessage Empty array provided.
     */
    public function it_throws_EmptyCollectionException_if_an_empty_array_is_provided()
    {
        QueryBuilder::create([]);
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotValidCriterionOperatorException
     * @expectedExceptionMessage $$$$ is not a valid operator.
     */
    public function it_throws_NotValidCriterionOperatorException_if_an_invalid_criteria_operator_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            QueryBuilder::create($array)
                ->addCriterion('id', 12, '$$$$');
        }
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotValidKeyElementInArrayException
     * @expectedExceptionMessage not-existing-key is not a valid key in the array.
     */
    public function it_throws_NotValidKeyElementInArrayException_if_a_not_valid_element_key_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            QueryBuilder::create($array)
                ->addCriterion('not-existing-key', 'Ervin Howell')
                ->getResults();
        }
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotValidKeyElementInArrayException
     * @expectedExceptionMessage not-existing-key is not a valid key in the array.
     */
    public function it_throws_NotValidKeyElementInArrayException_if_a_not_valid_nested_element_key_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            QueryBuilder::create($array)
                ->addCriterion('company.not-existing-key', 'Yost and Sons')
                ->getResults();
        }
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotValidSortingOperatorException
     * @expectedExceptionMessage not wrong sorting operator is not a valid sorting operator.
     */
    public function it_throws_NotValidSortingOperatorException_if_an_invalid_sorting_operator_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            QueryBuilder::create($array)
                ->addCriterion('name', 'Ervin Howell')
                ->sortedBy('name', 'not wrong sorting operator');
        }
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotValidLimitsOfArrayException
     * @expectedExceptionMessage string must be an integer.
     */
    public function it_throws_NotValidLimitsOfArrayException_if_an_invalid_offset_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)->limit('string', 13);
        }
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotValidLimitsOfArrayException
     * @expectedExceptionMessage string must be an integer.
     */
    public function it_throws_NotValidLimitsOfArrayException_if_an_invalid_limit_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)->limit(0, 'string');
        }
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotValidLimitsOfArrayException
     * @expectedExceptionMessage 432 must be an < than 13.
     */
    public function it_throws_NotValidLimitsOfArrayException_if_an_offset_is_grater_than_length_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)->limit(432, 13);
        }
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotValidLimitsOfArrayException
     * @expectedExceptionMessage 1333 must be an > than array count.
     */
    public function it_throws_NotValidLimitsOfArrayException_if_a_limit_is_greater_than_array_count_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)->limit(0, 1333);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_with_no_criteria_applied()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array);

            $this->assertCount(10, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_simple_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('name', 'Ervin Howell');

            $this->assertCount(1, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_greater_than_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('id', '3', '>');

            $this->assertCount(7, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_greater_than_equals_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('id', '3', '>=');

            $this->assertCount(8, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_less_than_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('id', '3', '<');

            $this->assertCount(2, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_less_than_equals_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('id', '3', '<=');

            $this->assertCount(3, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_not_equals_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('name', 'Ervin Howell', '!=');

            $this->assertCount(9, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_contains_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('name', 'clement', 'CONTAINS');

            $this->assertCount(2, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_array_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('name', ['Leanne Graham', 'Ervin Howell', 'Clementine Bauch'], 'ARRAY');

            $this->assertCount(3, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_array_inversed_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('tags', 'pinapple', 'ARRAY_INVERSED')
                ->sortedBy('name', 'ASC');

            $results = $qb->getResults();

            $this->assertCount(9, $results);
            $this->assertEquals('Chelsey Dietrich', $results[0]['name']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_concatenated_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('name', 'clement', 'CONTAINS')
                ->addCriterion('id', '6', '<')
                ->sortedBy('id', 'ASC')
            ;

            $results = $qb->getResults();

            $this->assertCount(1, $results);
            $this->assertEquals(3, $results[0]['id']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_array_inversed_query_with_sorting_and_limits()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('tags', 'pinapple', 'ARRAY_INVERSED')
                ->sortedBy('id', 'DESC')
                ->limit(0, 3);

            $results = $qb->getResults();

            $this->assertCount(3, $results);
            $this->assertEquals(4, $results[0]['id']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_a_nested_key()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('company.name', 'Yost and Sons')
                ->sortedBy('id', 'DESC');

            $results = $qb->getResults();

            $this->assertEquals(1, $qb->getCount());
            $this->assertEquals(9, $results[0]['id']);
            $this->assertEquals('Glenna Reichert', $results[0]['name']);
        }
    }
}
