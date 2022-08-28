<?php

declare(strict_types=1);

namespace App\Modules\User\Api\Requests;

use App\Common\Api\Requests\BaseRequest;
use App\Modules\User\Domain\Entities\User;
use Core\Components\Http\Enums\RequestMethod;

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
