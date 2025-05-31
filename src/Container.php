<?php

namespace Forgeon;

use ReflectionClass;

class Container
{
    public function resolve(string $class)
    {
        $refClass = new ReflectionClass($class);
        $constructor = $refClass->getConstructor();

        if (!$constructor) {
            return new $class();
        }

        $params = [];

        foreach ($constructor->getParameters() as $param) {

            $paramType = $param->getType();
            
            if ($paramType && !$paramType->isBuiltin()) {
                $params[] = $this->resolve($paramType->getName());
            }
        }

        return $refClass->newInstanceArgs($params);
    }
}
