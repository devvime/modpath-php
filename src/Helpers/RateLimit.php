<?php

namespace ModPath\Helpers;

use Redis;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\RedisStorage;

class RateLimit
{
    public static function execute(
        string $host = '127.0.0.1',
        int $port = 6379,
        int $time = 60,
        int $limit = 5
    ): bool {
        $redis = new Redis();
        $redis->connect($host, $port);

        $storage = new RedisStorage($redis);

        $factory = new RateLimiterFactory([
            'id' => 'api_ip_limit',
            'policy' => 'fixed_window',
            'limit' => $limit,
            'interval' => "{$time} seconds",
        ], $storage);

        $identifier = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $limiter = $factory->create($identifier);

        $usage = $limiter->consume();

        if (!$usage->isAccepted()) {
            http_response_code(429);
            echo 'Too Many Requests';
            return false;
        }

        return true;
    }
}
