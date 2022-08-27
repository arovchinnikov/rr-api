<?php

declare(strict_types=1);

namespace Core\Modules\Data\ValueManagers;

class Text
{
    public static function snakeCaseToLowerCamelCase(string $value): string
    {
        return str_replace("_", "", lcfirst(ucwords($value, " /_")));
    }
}
