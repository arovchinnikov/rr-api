<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Collections;

use Core\Base\Interfaces\Types\ToArray;

class UsersList implements ToArray
{
    protected int $total;
    protected array $users = [];

    public function __construct(array $users = [])
    {
        if (empty($users)) {
            return;
        }

        foreach ($users as $user) {
            $this->setUser($user);
        }
    }

    public function setUser(array $user): void
    {
        $this->users[] = [
            'id' => $user['id'],
            'login' => $user['login'],
            'nickname' => $user['nickname'],
            'email' => $user['email'],
            'access_level' => $user['access_level'],
            'is_deleted' => (bool)$user['deleted_at'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'],
        ];
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'users' => array_values($this->users)
        ];
    }
}
