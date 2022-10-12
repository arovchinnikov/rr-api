<?php

declare(strict_types=1);

namespace Core\Components\Config;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGet(): void
    {
        Config::set('test.test1', 42);
        $value = Config::get('test.test1');

        $this->assertEquals(42, $value);

        Config::set('test.test2', ['arrayValue' => 56]);

        $value = Config::get('test.test2.arrayValue');

        $this->assertEquals(56, $value);
    }

    public function testFromConfigFile(): void
    {
        Config::setPath(__DIR__ . '/testconfig');
        Config::update();

        $this->assertEquals(100, Config::get('configfile.test'));
    }
}
