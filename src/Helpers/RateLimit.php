<?php

namespace ModPath\Helpers;

use Redis;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\RedisStorage;

class RateLimit
{
    public static function execute(
        ?string $identifier = null,
        string $host = 'redis',
        int $port = 6379,
        int $time = 60,
        int $maxRequests = 5
    ): bool {
        try {
            $redis = new Redis();
            $redis->connect($host, $port);

            $storage = new RedisStorage($redis);

            $factory = new RateLimiterFactory([
                'id' => 'api_ip_limit',
                'policy' => 'fixed_window',
                'limit' => $maxRequests,
                'interval' => "{$time} seconds",
            ], $storage);

            $identifier = $identifier ?? ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
            $limiter = $factory->create($identifier);

            $usage = $limiter->consume();

            if (!$usage->isAccepted()) {
                http_response_code(429);
                echo 'Too Many Requests';
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            error_log("RateLimit error: " . $e->getMessage());
            return true;
        }
    }
}
