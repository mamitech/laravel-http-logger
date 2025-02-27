<?php

namespace Mamitech\LaravelHttpLogger\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mamitech\LaravelHttpLogger\Utils\DataTruncater;

class HttpLoggerMiddleware
{
    protected $request;
    protected $response;
    protected $duration;

    public function handle($request, \Closure $next)
    {
        $this->request = $request;
        $startTime = microtime(true);

        $this->response = $next($request);

        $this->duration = microtime(true) - $startTime; // this is in second
        $this->duration = (int) ($this->duration * 1000); // to make it in millisecond

        $this->logHttpRequest();

        return $this->response;
    }

    protected function logHttpRequest()
    {
        if (!config('http-logger.enabled')) {
            return;
        }

        if ((int) config('http-logger.sampling_rate') <= rand(0, 99)) {
            return;
        }

        try {
            $logData = $this->getLogData();
            $jsoned = json_encode($logData, JSON_PARTIAL_OUTPUT_ON_ERROR);
            if (!$jsoned) {
                return;
            }

            Log::channel(config('http-logger.log_channel'))->info($jsoned);
        } catch (\Throwable $e) { // For PHP 7
            Log::channel('errorlog')->error($e);
        }
    }

    protected function getLogData()
    {
        $request = $this->request;
        $response = $this->response;

        # only record json typed response body
        $responseContent = json_decode($response->getContent(), true) ?? '[FILTERED] non-json response';

        $requestBody = DataTruncater::truncateData($this->request->getContent());
        $responseBody = DataTruncater::truncateData($responseContent);

        // Check:
        // @link https://www.elastic.co/guide/en/ecs/current/ecs-url.html
        // @link https://www.elastic.co/guide/en/ecs/current/ecs-http.html
        // @link https://www.elastic.co/guide/en/ecs/current/ecs-user.html
        $data = [
            'url' => [
                'domain' => $request->root(),
                'full' => $request->fullUrl(),
                'path' => $request->path(),
            ],
            'http' => [
                'request' => [
                    'method' => $request->method(),
                    'referrer' => $request->headers->get('referrer'),
                    'headers' => $request->header(),
                    'body' => [
                        'content' => $requestBody,
                    ],
                    'params' => $this->request->all(),
                ],

                'response' => [
                    'status_code' => $response->getStatusCode(),
                    'headers' => $response->headers->allPreserveCaseWithoutCookies(),
                    'body' => [
                        'content' => $responseBody,
                    ],
                ],
            ],
            'user' => [
                'id' => Auth::id(),
            ],
            'event' => [
                'duration' => $this->duration,
                'action' => $request->route()->getActionName(),
            ],
        ];

        return $this->filterHttpLogData($data);
    }

    protected function filterHttpLogData($data)
    {
        $forbiddenKeys = config('http-logger.forbid_keys');

        if (empty($forbiddenKeys)) {
            return $data;
        }

        foreach ($data as $key => $_) {
            foreach($forbiddenKeys as $forbidKey) {
                if (strpos($key, $forbidKey) !== false) {
                    $data[$key] = '**';
                }
            }

            if (is_array($data[$key])) {
                $data[$key] = $this->filterHttpLogData($data[$key]);
            }
        }

        return $data;
    }
}
