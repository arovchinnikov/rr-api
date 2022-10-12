<?php

declare(strict_types=1);

namespace Core\Components\Config;

use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    public function testGet(): void
    {
        Env::setFile(__DIR__ . '/testenv/.env');
        Env::update();

        $this->assertEquals('TEST', Env::get('TEST_VALUE'));
    }
}
