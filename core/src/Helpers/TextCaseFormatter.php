<?php

declare(strict_types=1);

namespace Core\Helpers;

class TextCaseFormatter
{
    public static function snakeCaseToLowerCamelCase(string $value): string
    {
        return str_replace("_", "", lcfirst(ucwords($value, " /_")));
    }
}
