<?php

namespace Mamitech\LaravelHttpLogger;

use Illuminate\Support\ServiceProvider;

class HttpLoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-http-logger');

        $this->app->singleton('laravel-http-logger', function () {
            return new HttpLogger;
        });
    }
}
