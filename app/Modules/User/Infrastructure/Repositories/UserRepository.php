<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Repositories;

use App\Common\Api\Filters\BaseFilter;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\ValueObjects\Login;
use App\Modules\User\Infrastructure\Collections\UsersList;

class UserRepository extends \App\Common\Infrastructure\Repositories\PostgresRepository
{
    public function getById(int $id): ?User
    {
        $sql = 'SELECT * FROM users WHERE id = ' . $id;

        $result = $this->connection->query($sql);

        if (empty($result)) {
            return null;
        }

        $user = new User();
        $user->setValues($result);

        return $user;
    }

    public function getUsers(BaseFilter $filter): UsersList
    {
        $filter->pagination() ?? $filter->setPagination(20, 1);

        $sql = 'SELECT * FROM users' . $filter->toSql();

        $total = $this->connection->query(str_replace('*','COUNT(*)' , $sql), $filter->getParams());
        $result = $this->connection->query($sql . $filter->pagination(), $filter->getParams()) ?? [];

        $list = new UsersList($result);
        $list->setTotal($total);

        return $list;
    }

    public function userByLoginExists(string|Login $login): bool
    {
        if ($login instanceof Login) {
            $login = $login->getValue();
        }

        $sql = 'SELECT id FROM users WHERE login = :login';

        $result = $this->connection->query($sql, ['login' => $login]);

        return (bool)$result;
    }
}
