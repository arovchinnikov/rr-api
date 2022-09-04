<?php

declare(strict_types=1);

namespace App\Modules\User\Api\Controllers;

use App\Common\Api\Controllers\BaseController;
use App\Modules\User\Api\Filters\UsersFilter;
use App\Modules\User\Api\Requests\UserRequest;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Services\Exceptions\UserServiceException;
use App\Modules\User\Domain\Services\UserService;
use App\Modules\User\Infrastructure\Collections\UsersList;
use Core\Components\Http\Enums\ResponseCode;
use Core\Exceptions\CoreException;
use Core\Exceptions\ValidationException;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function read(): ?User
    {
        $id = $this->request->urlParams['id'];
        $user = $this->userService->getById($id);

        if (empty($user)) {
            $this->response->setCode(ResponseCode::noContent);
            return null;
        }

        return $user;
    }

    public function list(UsersFilter $filter): UsersList
    {
        return $this->userService->getUsers($filter);
    }

    /**
     * @throws ValidationException
     * @throws UserServiceException|CoreException
     */
    public function create(UserRequest $request): array
    {
        $request->validate();

        $user = $this->userService->createUser($request->user);

        return [
            'id' => $user->id,
            'login' => $user->login,
            'email' => $user->email,
            'password' => $request->user->password->getRaw()
        ];
    }
}
