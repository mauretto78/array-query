<?php
/**
 * This file is part of the ArrayQuery package.
 *
 * (c) Mauro Cassani<https://github.com/mauretto78>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function it_should_get_the_first_result_with_no_criteria_applied()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array);

            $first = $qb->getFirstResult();

            $this->assertEquals(1, $first['id']);
            $this->assertEquals('Leanne Graham', $first['name']);
            $this->assertEquals('Sincere@april.biz', $first['email']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_the_last_result_with_no_criteria_applied()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array);

            $last = $qb->getLastResult();

            $this->assertEquals(10, $last['id']);
            $this->assertEquals('Clementina DuBuque', $last['name']);
            $this->assertEquals('Rey.Padberg@karina.biz', $last['email']);
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
                ->addCriterion('name', ['Leanne Graham', 'Ervin Howell', 'Clementine Bauch'], 'IN_ARRAY');

            $this->assertCount(3, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_IN_ARRAY_INVERSED_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('tags', 'pinapple', 'IN_ARRAY_INVERSED')
                ->sortedBy('name', 'ASC');

            $results = $qb->getResults();

            $this->assertCount(9, $results);
            $this->assertEquals('Chelsey Dietrich', $results[0]['name']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_array_match_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('tags', ['pear-pie', 'apple-pie'], 'ARRAY_MATCH')
                ->sortedBy('name', 'ASC');

            $results = $qb->getResults();

            $this->assertCount(6, $results);
            $this->assertEquals('Clementina DuBuque', $results[0]['name']);
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
    public function it_should_get_results_from_a_IN_ARRAY_INVERSED_query_with_sorting_and_limits()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('tags', 'pinapple', 'IN_ARRAY_INVERSED')
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

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_starts_with()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('username', 'Ka', 'STARTS_WITH')
                ->sortedBy('username', 'ASC');

            $results = $qb->getResults();

            $this->assertEquals(2, $qb->getCount());
            $this->assertEquals(5, $results[0]['id']);
            $this->assertEquals('Kamren', $results[0]['username']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_ends_with()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('email', 'biz', 'ENDS_WITH')
                ->sortedBy('email', 'DESC');

            $results = $qb->getResults();

            $this->assertEquals(3, $qb->getCount());
            $this->assertEquals(7, $results[0]['id']);
            $this->assertEquals('Telly.Hoeger@billy.biz', $results[0]['email']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_gt_date()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('registration_date', '01/05/2017', 'GT_DATE', 'd/m/Y')
                ->sortedBy('id', 'DESC');

            $results = $qb->getResults();

            $this->assertEquals(6, $qb->getCount());
            $this->assertEquals(7, $results[0]['id']);
            $this->assertEquals('Kurtis Weissnat', $results[0]['name']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_gte_date()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('registration_date', '01/05/2017', 'GTE_DATE', 'd/m/Y')
                ->sortedBy('id', 'DESC');

            $results = $qb->getResults();

            $this->assertEquals(7, $qb->getCount());
            $this->assertEquals(7, $results[0]['id']);
            $this->assertEquals('Kurtis Weissnat', $results[0]['name']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_lt_date()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('registration_date', '01/05/2017', 'LT_DATE', 'd/m/Y')
                ->sortedBy('id', 'DESC');

            $results = $qb->getResults();

            $this->assertEquals(3, $qb->getCount());
            $this->assertEquals(10, $results[0]['id']);
            $this->assertEquals('Clementina DuBuque', $results[0]['name']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_lte_date()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('registration_date', '01/05/2017', 'LTE_DATE', 'd/m/Y')
                ->sortedBy('id', 'DESC');

            $results = $qb->getResults();

            $this->assertEquals(4, $qb->getCount());
            $this->assertEquals(10, $results[0]['id']);
            $this->assertEquals('Clementina DuBuque', $results[0]['name']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_equals_date()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('update_date', '2017-08-30', 'EQUALS_DATE')
                ->sortedBy('id', 'ASC');

            $results = $qb->getResults();

            $this->assertEquals(2, $qb->getCount());
            $this->assertEquals(5, $results[0]['id']);
            $this->assertEquals('Chelsey Dietrich', $results[0]['name']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_equals_date_sorted_by_date_asc()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('registration_date', '01/05/2017', 'LTE_DATE', 'd/m/Y')
                ->sortedBy('registration_date', 'DATE_ASC', 'd/m/Y');

            $results = $qb->getResults();

            $this->assertEquals(4, $qb->getCount());
            $this->assertEquals(10, $results[0]['id']);
            $this->assertEquals('Clementina DuBuque', $results[0]['name']);
        }
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_equals_date_sorted_by_date_desc()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('registration_date', '01/05/2017', 'LTE_DATE', 'd/m/Y')
                ->sortedBy('registration_date', 'DATE_DESC', 'd/m/Y');

            $results = $qb->getResults();

            $this->assertEquals(4, $qb->getCount());
            $this->assertEquals(3, $results[0]['id']);
            $this->assertEquals('Clementine Bauch', $results[0]['name']);
        }
    }

    /**
    * @test
    */
    public function it_should_get_results_from_a_query_with_joins()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Mauro Cassani',
                'id_category' => 3,
                'email' => 'assistenza@easy-grafica.com'
            ],[
                'id' => 2,
                'name' => 'Mario Rossi',
                'id_category' => 3,
                'email' => 'mario.rossi@gmail.com'
            ],[
                'id' => 3,
                'name' => 'Maria Bianchi',
                'id_category' => 1,
                'email' => 'maria.bianchi@gmail.com'
            ]
        ];
        $category = [
            'id' => 3,
            'name' => 'Web Developer'
        ];

        $qb = QueryBuilder::create($users)
            ->join($category, 'category', 'id_category', 'id')
            ->addCriterion('category.id', 3);

        $results = $qb->getResults();
        $expectedArray = [
            [
                'id' => 1,
                'name' => 'Mauro Cassani',
                'id_category' => 3,
                'email' => 'assistenza@easy-grafica.com',
                'category'=> [
                    'id' => 3,
                    'name' => 'Web Developer'
                ]
            ],
            [
                'id' => 2,
                'name' => 'Mario Rossi',
                'id_category' => 3,
                'email' => 'mario.rossi@gmail.com',
                'category'=> [
                    'id' => 3,
                    'name' => 'Web Developer'
                ]
            ]
        ];

        $this->assertEquals($results, $expectedArray);
        $this->assertEquals(2, $qb->getCount());
    }

    /**
     * @test
     */
    public function it_should_get_results_from_a_query_with_aliases()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array)
                ->addCriterion('name as n', 'Ervin Howell')
                ->addCriterion('username as user', 'Antonette')
                ->addCriterion('address.street as add', 'Victor Plains');

            $this->assertCount(1, $qb->getResults());
            $this->assertArrayHasKey('n', $qb->getResults()[1]);
            $this->assertArrayHasKey('user', $qb->getResults()[1]);
            $this->assertArrayHasKey('add', $qb->getResults()[1]);
        }
    }
}
