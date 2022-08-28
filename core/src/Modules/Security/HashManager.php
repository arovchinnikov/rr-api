<?php

declare(strict_types=1);

namespace Core\Modules\Security;

class HashManager
{
    private string $alg = 'sha512';
    private string $salt = '';

    public function prepare(string $string): string
    {
        return hash($this->alg, $string . $this->salt);
    }

    public function compare(string $string, string $hash): bool
    {
        return $this->prepare($string) === $hash;
    }

    public function setAlg(string $value): void
    {
        $this->alg = $value;
    }

    public function setSalt(string $value): void
    {
        $this->salt = $value;
    }
}
