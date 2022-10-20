<?php

declare(strict_types=1);

namespace Core\Helpers;

use PHPUnit\Framework\TestCase;

class TextCaseFormatterTest extends TestCase
{
    public function testSnakeCaseToLowerCamelCase(): void
    {
        $this->assertEquals('testMessage', TextCaseFormatter::snakeCaseToLowerCamelCase('test_message'));
    }
}
