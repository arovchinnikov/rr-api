<?php

declare(strict_types=1);

namespace App\BaseApi\Services;

use App\BaseApi\Aggregates\User\UsersList;
use App\BaseApi\DataObjects\User\Nickname;
use App\BaseApi\Http\Filters\UsersFilter;
use App\BaseApi\Entities\User;
use App\BaseApi\Repositories\UserRepository;
use App\BaseApi\Services\Exceptions\UserServiceException;
use Carbon\Carbon;
use Core\Base\Exceptions\CoreException;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->getById($id);
    }

    public function getUsers(UsersFilter $filter): UsersList
    {
        return $this->userRepository->getUsers($filter);
    }

    /**
     * @throws UserServiceException|CoreException
     */
    public function createUser(User $user): ?User
    {
        if ($this->userRepository->userByLoginExists($user->login)) {
            UserServiceException::userWithLoginExists();
        }

        if ($user->nickname === null) {
            $nickname = new Nickname($user->login->getValue());
            $user->setNickname($nickname);
        }

        $user->setCreatedAt(Carbon::now());

        return $this->userRepository->saveModel($user);
    }
}
