<?php

declare(strict_types=1);

namespace App\Modules\User\Api\Requests;

use App\Common\Api\Requests\BaseRequest;

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
