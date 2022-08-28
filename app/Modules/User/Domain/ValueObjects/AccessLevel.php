<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

enum AccessLevel: string
{
    case user = 'user';
}
