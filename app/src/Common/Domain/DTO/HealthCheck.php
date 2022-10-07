<?php

declare(strict_types=1);

namespace App\Common\Domain\DTO;

use Carbon\Carbon;
use Core\Base\Interfaces\Types\ToArray;

class HealthCheck implements ToArray
{
    public function toArray(): array
    {
        return [
            'status' => 'online',
            'time' => Carbon::now()->format('Y-m-d H:m:s')
        ];
    }
}
