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

use ArrayQuery\Helpers\ConsistencyChecker;
use PHPUnit\Framework\TestCase;

class ConsistencyCheckerTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_false_if_an_not_consistent_array_is_provided()
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

        $this->assertFalse(ConsistencyChecker::isValid($array));
    }

    /**
     * @test
     */
    public function it_returns_false_if_an_not_consistent_array_with_nested_keys_is_provided()
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
                        'extra' => 55,
                    ]
                ],
            ],
        ];

        $this->assertFalse(ConsistencyChecker::isValid($array));
    }

    /**
     * @test
     */
    public function it_returns_false_if_an_not_consistent_element_is_provided()
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
        ];

        $element = [
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
        ];

        $this->assertTrue(ConsistencyChecker::isValid($array));
        $this->assertFalse(ConsistencyChecker::isElementValid($element, $array));
    }
}
