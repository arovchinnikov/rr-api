<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\Entities;

use App\Common\Domain\Entities\BaseModel;
use App\Modules\User\Domain\ValueObjects\AccessLevel;
use App\Modules\User\Domain\ValueObjects\Email;
use App\Modules\User\Domain\ValueObjects\Login;
use App\Modules\User\Domain\ValueObjects\Nickname;
use App\Modules\User\Domain\ValueObjects\Password;
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
