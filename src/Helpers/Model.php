<?php

namespace ModPath\Helpers;

class Model
{
    function fields(string $modelClass): array
    {
        $reflection = new \ReflectionClass($modelClass);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return [];
        }

        return array_map(
            fn($param) => $param->getName(),
            $constructor->getParameters()
        );
    }

    public static function create(array $data): static
    {
        $reflection = new \ReflectionClass(static::class);
        $constructor = $reflection->getConstructor();

        $args = [];

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $args[] = $data[$name] ?? null;
        }

        return new static(...$args);
    }
}