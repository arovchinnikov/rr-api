<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Services;

use App\Modules\User\Api\Filters\UsersFilter;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Services\Exceptions\UserServiceException;
use App\Modules\User\Domain\ValueObjects\Nickname;
use App\Modules\User\Infrastructure\Collections\UsersList;
use App\Modules\User\Infrastructure\Repositories\UserRepository;
use Carbon\Carbon;
use Core\Exceptions\CoreException;

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
