<?php

declare(strict_types=1);

namespace App\BaseApi\DataObjects;

use Carbon\Carbon;
use Core\Base\Interfaces\Types\ToArray;

class HealthCheck implements ToArray
{
    public function toArray(): array
    {
        return [
            'success' => true,
            'time' => Carbon::now()->format('Y-m-d H:m:s')
        ];
    }
}
