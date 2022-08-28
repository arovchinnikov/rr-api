<?php

declare(strict_types=1);

namespace App\BaseApi\Http\Requests;

use App\Common\Base\Http\BaseRequest;

class UsersRequest extends BaseRequest
{
    public function fields(): array
    {
        return [
            'deleted' => [
                'type' => 'bool'
            ],
        ];
    }
}
