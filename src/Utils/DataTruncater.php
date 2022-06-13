<?php

namespace Mamitech\LaravelHttpLogger\Utils;

class DataTruncater
{
    public static function truncateData($data) {
        $truncateStr = '[TRUNCATED] : data is too long';

        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    $v = serialize($v);
                }

                if (mb_strlen($v) > 512) {
                    $data[$k] = $truncateStr;
                }
            }
        }

        if (is_string($data) && mb_strlen($data) > 512) {
            return $truncateStr;
        }

        return $data;
    }
}