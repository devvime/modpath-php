<?php

namespace ModPath\Core;

use ReflectionClass;
use ReflectionNamedType;

class Container
{
    public function resolve(string $class): object
    {
        $refClass = new ReflectionClass($class);

        // No constructor? Direct instance
        if (!$refClass->getConstructor()) {
            return new $class();
        }

        $params = [];

        foreach ($refClass->getConstructor()->getParameters() as $param) {
            $type = $param->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $params[] = $this->resolve($type->getName());
            }
        }

        return $refClass->newInstanceArgs($params);
    }
}
