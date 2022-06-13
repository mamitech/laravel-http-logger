<?php

namespace Mamitech\LaravelHttpLogger\Test\Utils;

use Mamitech\LaravelHttpLogger\Utils\DataTruncater;

class DataTruncaterTest extends \Orchestra\Testbench\TestCase
{
    const TRUNCATE_STRING = '[TRUNCATED] : data is too long';

    public function test_truncate_data_with_short_string()
    {
        $str = 'a short string';
        $this->assertEquals($str, DataTruncater::truncateData($str));
    }

    public function test_truncate_data_with_long_string()
    {
        $str = str_repeat('ok', 512);
        $this->assertEquals(self::TRUNCATE_STRING , DataTruncater::truncateData($str));
    }

    public function test_truncate_data_with_short_array()
    {
        $arr = [
            'a' => 'short',
            'array' => 'to',
            'be' => 'tested'
        ];
        $this->assertEquals($arr, DataTruncater::truncateData($arr));
    }

    public function test_truncate_data_with_long_array()
    {
        $shortArr = [
            'this' => 'short',
            'also' => 'short',
        ];

        $longArr = array_merge($shortArr, [
            'long' => [
                'sentence' => str_repeat('lorem', 512)
            ]
        ]);

        $expectedArray = array_merge($shortArr, [
            'long' => self::TRUNCATE_STRING
        ]);

        $this->assertEquals($expectedArray, DataTruncater::truncateData($longArr));
    }
}
