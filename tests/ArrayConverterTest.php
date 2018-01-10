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

use ArrayQuery\Helpers\ArrayHelper;
use PHPUnit\Framework\TestCase;

class ArrayConverterTest extends TestCase
{
    /**
     * @test
     */
    public function it_converts_a_plain_array_in_an_object_array()
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

        $objectArray = ArrayHelper::convertToObjectArray($array);
        foreach ($objectArray as $item) {
            $this->assertInstanceOf(\stdClass::class, $item);
        }
    }

    /**
     * @test
     */
    public function it_converts_an_object_array_in_a_plain_array()
    {
        $a = new \stdClass();
        $a->id = 1;
        $a->title = 'Leanne Graham';
        $a->email = 'Sincere@april.biz';

        $b = new \stdClass();
        $b->id = 2;
        $b->title = 'Ervin Howell';
        $b->email = 'Shanna@melissa.tv';

        $array = [
            $a,
            $b
        ];

        $plainArray = ArrayHelper::convertToPlainArray($array);
        foreach ($plainArray as $item) {
            $this->assertTrue(is_array($item));
        }
    }
}
