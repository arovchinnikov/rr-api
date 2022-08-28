<?php

declare(strict_types=1);

namespace App\BaseApi\Http\Controllers;

use App\BaseApi\Aggregates\User\UsersList;
use App\BaseApi\Entities\User;
use App\BaseApi\Http\Filters\UsersFilter;
use App\BaseApi\Http\Requests\UserRequest;
use App\BaseApi\Services\Exceptions\UserServiceException;
use App\BaseApi\Services\UserService;
use App\Common\Base\Http\BaseController;
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
