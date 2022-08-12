<?php

declare(strict_types=1);

namespace Core\Modules\Data;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Container
{
    private static array $dependencies = [];

    public static function get(string $classname): ?object
    {
        if (empty(self::$dependencies[$classname])) {
            return self::resolve($classname);
        }

        return self::$dependencies[$classname];
    }

    public static function getAll(): array
    {
        return self::$dependencies;
    }

    /**
     * @throws ReflectionException
     */
    public static function resolve(string $classname, bool $saveDependency = true): object
    {
        $reflection = new ReflectionClass($classname);
        $dependencies = self::getMethodDependencies($reflection->getConstructor());

        $args = [];
        foreach ($dependencies as $dependency) {
            $args[] = self::resolve($dependency);
        }

        $object = new $classname(...$args);

        if ($saveDependency) {
            self::$dependencies[$classname] = $object;
        }

        return $object;
    }

    private static function getMethodDependencies(?ReflectionMethod $method): array
    {
        if (empty($method)) {
            return [];
        }

        $args = [];
        foreach ($method->getParameters() as $parameter) {
            $args[] = $parameter->getType()->getName();
        }

        return $args;
    }
}
