<?php

declare(strict_types=1);

namespace App\BaseApi\Http\Requests;

use App\BaseApi\Entities\User;
use App\Common\Base\Http\BaseRequest;
use Core\Modules\Http\Enums\RequestMethod;

/**
 * @property User $user
 */
class UserRequest extends BaseRequest
{
    public function fields(): array
    {
        return [
            'user' => [
                'method' => RequestMethod::post,
                'required' => true,
                'type' => User::class
            ]
        ];
    }
}
