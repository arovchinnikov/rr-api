<?php

declare(strict_types=1);

namespace App\Modules\User\Api;

use App\Common\Base\Http\BaseController;
use App\Modules\User\Api\Filters\UsersFilter;
use App\Modules\User\Api\Requests\UserRequest;
use App\Modules\User\Domain\Collections\UsersList;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Services\Exceptions\UserServiceException;
use App\Modules\User\Domain\Services\UserService;
use Core\Base\Exceptions\CoreException;
use Core\Base\Exceptions\ValidationException;
use Core\Modules\Http\Enums\ResponseCode;

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
     * @throws \App\Modules\User\Domain\Services\Exceptions\UserServiceException|CoreException
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
