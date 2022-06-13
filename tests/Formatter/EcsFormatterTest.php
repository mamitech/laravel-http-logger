<?php

namespace Mamitech\LaravelHttpLogger\Test\Formatter;

use Mamitech\LaravelHttpLogger\Formatter\EcsFormatter;

class EcsFormatterTest extends \Orchestra\Testbench\TestCase
{
    public function test_no_exception_thrown()
    {
        $this->assertNotNull(new EcsFormatter());
    }
}