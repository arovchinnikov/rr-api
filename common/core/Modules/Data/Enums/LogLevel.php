<?php

declare(strict_types=1);

namespace Core\Modules\Data\Enums;

enum LogLevel: int
{
    case debug = 1;
    case info = 2;
    case notice = 3;
    case warning = 4;
    case error = 5;
    case critical = 6;
    case alert = 7;
    case emergency = 8;
}
