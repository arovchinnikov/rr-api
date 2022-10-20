<?php

declare(strict_types=1);

namespace Core\Components\Dependencies;

use App\Common\Api\Filters\BaseFilter;
use App\Common\Api\Requests\BaseRequest;
use Core\Components\Dependencies\Interfaces\ContainerInterface;
use Core\Components\Http\Request;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Container implements ContainerInterface
{
    protected static array $items = [];

    public function get(string $classname): mixed
    {
        if (empty(self::$items[$classname])) {
            return self::resolve($classname);
        }

        return self::$items[$classname];
    }

    public function all(): array
    {
        return self::$items;
    }

    public function resolve(string $classname, bool $save = true): mixed
    {
        $reflection = new ReflectionClass($classname);
        $dependencies = self::getMethodDependencies($reflection->getConstructor());

        $args = [];
        foreach ($dependencies as $dependency) {
            $args[] = self::resolve($dependency, $save);
        }

        $object = new $classname(...$args);

        if ($save) {
            self::$items[$classname] = $object;
        }

        return $object;
    }

    /**
     * @throws ReflectionException
     */
    public function getActionArgs(string $classname, string $methodName, Request $request = null): array
    {
        $reflection = new ReflectionClass($classname);
        $dependencies = self::getMethodDependencies($reflection->getMethod($methodName));

        $args = [];
        foreach ($dependencies as $dependency) {
            $args[] = self::resolve($dependency, false);
        }

        return $args;
    }

    public function clean(): void
    {
        self::$items = [];
    }

    protected function getMethodDependencies(?ReflectionMethod $method): array
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
