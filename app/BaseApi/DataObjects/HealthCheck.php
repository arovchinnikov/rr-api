<?php

declare(strict_types=1);

namespace App\BaseApi\DataObjects;

use Carbon\Carbon;
use Core\Base\Classes\Http\ResponseObject;

class HealthCheck extends ResponseObject
{
    public function toArray(): array
    {
        return [
            'success' => true,
            'time' => Carbon::now()->format('Y-m-d H:m:s')
        ];
    }
}
