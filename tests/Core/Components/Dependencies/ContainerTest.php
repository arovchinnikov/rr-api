<?php

declare(strict_types=1);

namespace Core\Components\Dependencies;

use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected Container $container;

    public function setUp(): void
    {
        $this->container = new Container();
    }

    public function testGet(): void
    {
        $item = $this->container->get(ObjectWithDependencies::class);

        $this->assertInstanceOf(ObjectWithDependencies::class, $item);
        $this->assertInstanceOf(RequiredObject::class, $item->object);
        $this->assertCount(2, $this->container->all());

        $this->assertArrayHasKey(RequiredObject::class, $this->container->all());
        $this->assertArrayHasKey(ObjectWithDependencies::class, $this->container->all());

        $this->container->clean();
        $this->assertCount(0, $this->container->all());
    }

    public function testOldObjectState(): void
    {
        $item = $this->container->get(ObjectWithDependencies::class);
        $item->object = null;
        $sameItem = $this->container->get(ObjectWithDependencies::class);

        $this->assertEquals(null, $sameItem->object);
    }

    public function testResolve(): void
    {
        $this->container->clean();
        $item = $this->container->resolve(ObjectWithDependencies::class, false);

        $this->assertInstanceOf(ObjectWithDependencies::class, $item);
        $this->assertInstanceOf(RequiredObject::class, $item->object);

        $this->assertCount(0, $this->container->all());
    }

    public function testActionArgs(): void
    {
        $args = $this->container->getActionArgs(ObjectWithDependencies::class, 'actionWithArgs');

        $this->assertCount(2, $args);
        $this->assertInstanceOf(RequiredObject::class, $args[0]);
        $this->assertInstanceOf(RequiredObject::class, $args[1]->object);
    }
}

class ObjectWithDependencies
{
    public ?RequiredObject $object = null;

    public ?RequiredObject $argument = null;

    public ?ObjectWithConstructorArg $argument2 = null;


    public function __construct(RequiredObject $object)
    {
        $this->object = $object;
    }

    public function actionWithArgs(RequiredObject $argument, ObjectWithConstructorArg $argument2): void
    {
        $this->argument = $argument;
        $this->argument2 = $argument2;
    }
}

class ObjectWithConstructorArg
{
    public ?RequiredObject $object = null;

    public function __construct(RequiredObject $object)
    {
        $this->object = $object;
    }
}

class RequiredObject
{

}
