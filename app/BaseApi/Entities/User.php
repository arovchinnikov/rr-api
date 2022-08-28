<?php

declare(strict_types=1);

namespace App\BaseApi\Entities;

use App\BaseApi\DataObjects\User\AccessLevel;
use App\BaseApi\DataObjects\User\Email;
use App\BaseApi\DataObjects\User\Login;
use App\BaseApi\DataObjects\User\Nickname;
use App\BaseApi\DataObjects\User\Password;
use App\Common\Base\BaseModel;
use Carbon\Carbon;

/**
 * @property int $id
 * @property Login $login
 * @property Nickname $nickname
 * @property Email $email
 * @property Password $password
 * @property AccessLevel $accessLevel
 * @property ?string $createdAt
 * @property ?string $updatedAt
 * @property ?string $deletedAt
 */
class User extends BaseModel
{
    public static string $table = 'users';

    public function fields(): array
    {
        return [
            'id',
            'login' => Login::class,
            'nickname' => Nickname::class,
            'email' => Email::class,
            'password' => Password::class,
            'access_level' => AccessLevel::class,
            'created_at',
            'updated_at',
            'deleted_at'
        ];
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setLogin(Login $login): void
    {
        $this->login = $login;
    }

    public function setNickname(Nickname $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function setPassword(Password $password): void
    {
        $this->password = $password;
    }

    public function setAccessLevel(AccessLevel $accessLevel): void
    {
        $this->accessLevel = $accessLevel;
    }

    public function setCreatedAt(Carbon $date): void
    {
        $this->createdAt = $date->toString();
    }

    public function setUpdatedAt(Carbon $updatedAt): void
    {
        $this->updatedAt = $updatedAt->toString();
    }

    public function setDeletedAt(Carbon $deletedAt): void
    {
        $this->deletedAt = $deletedAt->toString();
    }
}
