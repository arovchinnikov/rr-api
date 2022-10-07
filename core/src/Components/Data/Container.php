<?php

declare(strict_types=1);

namespace Core\Components\Data;

use App\Common\Api\Filters\BaseFilter;
use App\Common\Api\Requests\BaseRequest;
use Core\Components\Http\Request;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Container
{
    private static array $dependencies = [];

    public static function get(string $classname): mixed
    {
        if (empty(self::$dependencies[$classname])) {
            return self::resolve($classname);
        }

        return self::$dependencies[$classname];
    }

    public static function all(): array
    {
        return self::$dependencies;
    }

    /**
     * @throws ReflectionException
     */
    public static function resolve(string $classname, bool $saveDependency = true): mixed
    {
        $reflection = new ReflectionClass($classname);
        $dependencies = self::getMethodDependencies($reflection->getConstructor());

        $args = [];
        foreach ($dependencies as $dependency) {
            $args[] = self::get($dependency);
        }

        $object = new $classname(...$args);

        if ($saveDependency) {
            self::$dependencies[$classname] = $object;
        }

        return $object;
    }

    /**
     * @throws ReflectionException
     */
    public static function resolveMethod(string $classname, string $methodName, Request $request = null): array
    {
        $reflection = new ReflectionClass($classname);
        $dependencies = self::getMethodDependencies($reflection->getMethod($methodName));

        $args = [];
        foreach ($dependencies as $dependency) {
            if (
                is_subclass_of($dependency, BaseRequest::class)
                || is_subclass_of($dependency, BaseFilter::class)
            ) {
                $args[] = new $dependency($request);
                continue;
            }

            $args[] = self::resolve($dependency, false);
        }

        return $args;
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
