<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\ValueObjects;

use Core\Base\DataValues\StringValue;

class Nickname extends StringValue
{
    protected string $nickname;

    public function __construct(string $nickname)
    {
        $this->nickname = $nickname;
    }

    public function validate(): void
    {
    }

    public function getValue(): ?string
    {
        return $this->nickname;
    }
}
