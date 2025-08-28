<?php

namespace ModPath\Helpers;

use Exception;

function rateLimit(
    string $host = '127.0.0.1',
    int $port = 6379,
    int $time = 60,
    int $limit = 5
): bool {
    try {
        $redis = new \Redis();
        $redis->connect($host, $port);

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = "ratelimit:$ip";

        $count = $redis->incr($key);

        if ($count === 1) {
            $redis->expire($key, $time);
        }

        return $count <= $limit;
    } catch (Exception $e) {
        error_log("Redis error: " . $e->getMessage());
        throw $e;
    }
}
