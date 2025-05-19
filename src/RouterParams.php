<?php

namespace Mini;

class RouterParams
{
    public static function buildRegexFromRoute(string $route): array
    {
        $paramTypes = [];
        $regex = preg_replace_callback('#\{(\w+)(?::(\w+))?\}#', function ($matches) use (&$paramTypes) {
            $param = $matches[1];
            $type = $matches[2] ?? 'string';

            $paramTypes[$param] = $type;

            $pattern = match ($type) {
                'int'   => '\d+',
                'uuid'  => '[0-9a-fA-F\-]{36}',
                'slug'  => '[a-z0-9\-]+',
                'string' => '[^/]+',
                default => '[^/]+',
            };

            return "(?P<$param>$pattern)";
        }, $route);

        return ["#^$regex$#", $paramTypes];
    }

    public static function extractTypedParams(array $matches, array $paramTypes): array
    {
        $params = [];

        foreach ($matches as $key => $value) {
            if (!is_int($key)) {
                $type = $paramTypes[$key] ?? 'string';

                $params[$key] = match ($type) {
                    'int'   => (int) $value,
                    'uuid'  => $value,
                    'slug'  => $value,
                    'string' => $value,
                    default => $value,
                };
            }
        }

        return $params;
    }
}