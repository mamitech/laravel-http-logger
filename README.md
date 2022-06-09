# Laravel HTTP Logger

Log all your HTTP request to ECS (Elastic Common Sheme) compliance format data into your selected logging channel.

## Installation

Add `mamitech/laravel-http-logger` to composer.json

Register the service provider in `providers` array in `config/app.php`

```php
'providers' => [
    // ...
    Mamitech\LaravelHTTPLogger::class,
    // ...
],
```

Publish the config

```
todo
```

Set logging channel in config to where you want the HTTP log being sent to.

```
todo
```

## Usage

Once you add the service provider and middleware to your Laravel application, all HTTP request should be logged to logging channel of your choice.

## Test

Run

```sh
./vendor/bin/phpunit
```

## Credits

-   [Mamitech](https://github.com/mamitech)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
