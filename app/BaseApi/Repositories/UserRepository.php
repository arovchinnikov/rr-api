<?php

declare(strict_types=1);

namespace App\BaseApi\Repositories;

use App\BaseApi\Aggregates\User\UsersList;
use App\BaseApi\DataObjects\User\Login;
use App\BaseApi\Entities\User;
use Core\Base\Classes\Http\BaseFilter;
use Core\Base\Classes\Http\DefaultFilter;
use Core\Base\Classes\Repositories\PostgresRepository;

class UserRepository extends PostgresRepository
{
    public function getById(int $id): ?User
    {
        $sql = 'SELECT * FROM users WHERE id = ' . $id;

        $result = $this->connection->query($sql);

        if (empty($result)) {
            return null;
        }

        $user = new User();
        d($result);
        $user->setValues($result);

        return $user;
    }

    public function getUsers(BaseFilter $filter = null): UsersList
    {
        $filter ?? $filter = new DefaultFilter();
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
