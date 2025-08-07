<?php

namespace ModPath\Core;

use ReflectionClass;
use ReflectionNamedType;

class Container
{
    public function resolve(string $class): object
    {
        $refClass = new ReflectionClass($class);

        // Sem construtor? Instancia direto
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
