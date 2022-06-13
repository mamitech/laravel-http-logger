<?php

namespace Mamitech\LaravelHttpLogger;

use Illuminate\Support\ServiceProvider;

class HttpLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('http-logger.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'http-logger');

        $this->app->singleton('http-logger', function () {
            return new HttpLogger;
        });
    }
}
