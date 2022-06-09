<?php

namespace Mamitech\LaravelHttpLogger\Test;

class HttpLoggerServiceProviderTest extends \Orchestra\Testbench\TestCase
{
    public function setup(): void
    {
        parent::setup();
        $this->app['router']->get('hi', function () {
            return 'hi there';
        });
    }

    public function test_no_exception_thrown()
    {
        $this->get('/hi')
            ->assertStatus(200);
    }

    protected function getPackageProviders($_)
    {
        return [
            'Mamitech\LaravelHttpLogger\HttpLoggerServiceProvider'
        ];
    }
}