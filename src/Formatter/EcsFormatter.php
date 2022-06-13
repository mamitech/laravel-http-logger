<?php

namespace Mamitech\LaravelHttpLogger\Formatter;

use Carbon\Carbon;
use Monolog\Formatter\NormalizerFormatter;

class EcsFormatter extends NormalizerFormatter
{
    public function format(array $record)
    {
        $record = parent::format($record);

        // @link https://www.elastic.co/guide/en/ecs/current/ecs-guidelines.html
        $coreFields = [
            '@timestamp' => Carbon::now()->toIso8601String(),
            'ecs.version' => '8.0.0',
            'host' => [
                'hostname' => gethostname(),
            ],
            'service' => [
                'name' => config('http-logger.service_name'),
                'environment' => \App::environment(),
            ]
        ];

        $additionalData = config('http-logger.additional_ecs_data');
        if (!empty($additionalData)) {
            $coreFields = array_merge($coreFields, $additionalData);
        }

        $logData = json_decode($record['message'], true);
        if (is_null($logData)) {
            $logData = ['message' => $record['message']];
        }
        $data = array_merge($coreFields, $logData);

        return $this->toJson($data);
    }
}