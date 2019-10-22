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
     */
    public function it_return_a_void_array_if_an_empty_array_is_provided()
    {
        $qb = QueryBuilder::create([]);

        $this->assertEquals([], $qb->getResults());
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotConsistentDataException
     * @expectedExceptionMessage Array provided has no consistent data.
     */
    public function it_throws_NotConsistentDataException_if_an_array_with_not_consistent_data_is_provided()
    {
        $array = [
            [
                'id' => 1,
                'title' => 'Leanne Graham',
                'email' => 'Sincere@april.biz',
                'rate' => 5,
                'company' => [
                    'name' => 'Romaguera-Jacobson',
                    'catchPhrase' => 'Face to face bifurcated interface',
                    'bs' => 'e-enable strategic applications'
                ]
            ],
            [
                'id' => 2,
                'title' => 'Ervin Howell',
                'email' => 'Shanna@melissa.tv',
                'rate' => 3,
                'company' => [
                    'name' => 'Robel-Corkery',
                    'catchPhrase' => 'Multi-tiered zero tolerance productivity',
                    'bs' => 'transition cutting-edge web services'
                ]
            ],
            [
                'id' => 3,
                'title' => 'Clementine Bauch',
                'email' => 'Nathan@yesenia.net',
                'rate' => 4,
                'company' => [
                    'name' => 'Keebler LLC',
                    'catchPhrase' => 'User-centric fault-tolerant solution',
                    'bs' => 'revolutionize end-to-end systems'
                ],
                'extra-field' => 'this is an extra field'
            ],
        ];

        QueryBuilder::create($array);
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
    public function it_should_get_results_from_a_query_with_deep_nested_aliases()
    {
        $array = [
            [
                'id' => 1,
                'name' => 'Mauro Cassani',
                'skills' => [
                    'web' => [
                        'php' => 5,
                        'web_design' => 5,
                        'css' => 4,
                    ]
                ],
            ],
            [
                'id' => 2,
                'name' => 'John Doe',
                'skills' => [
                    'web' => [
                        'php' => 3,
                        'web_design' => 1,
                        'css' => 5,
                    ]
                ],
            ],
            [
                'id' => 3,
                'name' => 'Maria Callas',
                'skills' => [
                    'web' => [
                        'php' => 1,
                        'web_design' => 3,
                        'css' => 3,
                    ]
                ],
            ],
        ];

        $qb = QueryBuilder::create($array)
            ->addCriterion('skills.web.php as php', 1, '>=')
            ->addCriterion('skills.web.web_design as web', 1, '>=')
        ;

        $results = $qb->getResults();

        $this->assertEquals($results[0]['php'], 5);
        $this->assertEquals($results[0]['web'], 5);
        $this->assertEquals($results[1]['php'], 3);
        $this->assertEquals($results[1]['web'], 1);
        $this->assertEquals($results[2]['php'], 1);
        $this->assertEquals($results[2]['web'], 3);
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
                ->addCriterion('address.street as str', 'Victor Plains')
                ->addCriterion('address.suite as sui', 'Suite 879')
            ;

            $result = $qb->getResults()[1];

            $this->assertCount(1, $qb->getResults());
            $this->assertEquals($result['n'], 'Ervin Howell');
            $this->assertEquals($result['user'], 'Antonette');
            $this->assertEquals($result['str'], 'Victor Plains');
            $this->assertEquals($result['sui'], 'Suite 879');
        }
    }

    /**
     * @test
     */
    public function it_should_get_the_first_result_from_a_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array);

            $result = $qb->getResult(3);

            $this->assertEquals(3, $result['id']);
            $this->assertEquals('Clementine Bauch', $result['name']);
            $this->assertEquals($qb->getResult(1), $qb->getFirstResult());
        }
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotConsistentDataException
     * @expectedExceptionMessage Element provided has no consistent data.
     */
    public function it_throws_NotConsistentDataException_if_an_element_with_not_consistent_data_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array);
            $element = [
                'id' => 234,
                'name' => 'Clementine Bauch 23',
                'username' => 'Samantha',
                'email' => 'Nathan@yesenia.net',
            ];

            $qb->addElement($element, 234);
        }
    }

    /**
     * @test
     */
    public function it_should_add_an_element_to_array()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array);
            $element = [
                'id' => 23,
                'name' => 'Clementine Bauch 23',
                'username' => 'Samantha',
                'email' => 'Nathan@yesenia.net',
                'address' => [
                    'street' =>  'Douglas Extension',
                    'suite' => 'Suite 847',
                    'city' => 'McKenziehaven',
                    'zipcode' => '59590-4157',
                    'geo' => [
                        'lat' => '-68.6102',
                        'lng' => '-47.0653'
                    ]
                ],
                'phone' => '1-463-123-4447',
                'website' => 'ramiro.info',
                'company' => [
                    'name' => 'Romaguera-Jacobson',
                    'catchPhrase' => 'Face to face bifurcated interface',
                    'bs' => 'e-enable strategic applications'
                ],
                'registration_date' => '01/05/2017',
                'update_date' =>  '2017-05-30',
                'tags' => [
                    'apple',
                    'pinapple',
                    'pear',
                    'apple-pie'
                ]
            ];

            $qb->addElement($element, 23);

            $this->assertCount(11, $qb->getResults());
        }
    }

    /**
     * @test
     * @expectedException \ArrayQuery\Exceptions\NotExistingElementException
     * @expectedExceptionMessage Element with key 232 does not exists.
     */
    public function it_throws_NotConsistentDataEfffxception_if_an_wrong_key_element_is_provided()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array);
            $qb->removeElement(232);
        }
    }

    /**
     * @test
     */
    public function it_should_remove_an_element_to_array()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array);
            $qb->removeElement(2);
            $qb->removeElement(3);

            $this->assertCount(8, $qb->getResults());
        }
    }

    /**
     * @test
     */
    public function it_should_get_shuffled_results_from_a_query()
    {
        foreach ($this->usersArrays as $array) {
            $qb = QueryBuilder::create($array);

            $this->assertCount(10, $qb->getShuffledResults());
        }
    }
}
