<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'enabled' => true,
    'log_channel' => 'syslog',
    'service_name' => 'laravel',

    'sampling_rate' => 100, // in percentage

    'additional_ecs_data' => null,

    /**
     * Value of the keys including a substring of one of the
     * element in this array will be replaced with ** in log.
     */
    'forbid_keys' => [
        'authorization',
        'cookie',
        'password',
        'token'
    ]
];