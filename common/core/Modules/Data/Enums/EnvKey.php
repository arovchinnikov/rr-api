<?php

declare(strict_types=1);

namespace Core\Modules\Data\Enums;

enum EnvKey: string
{
    case appName = 'APP_NAME';
    case appEnvironment = 'APP_ENVIRONMENT';
    case logStorage = 'LOG_STORAGE';

    public function type(): string
    {
        return match ($this) {
            default => 'string'
        };
    }
}
