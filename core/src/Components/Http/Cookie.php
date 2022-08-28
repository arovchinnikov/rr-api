<?php

declare(strict_types=1);

namespace Core\Components\Http;

use Carbon\Carbon;

class Cookie
{
    private string $key;
    private string $value;

    private bool $httpOnly;
    private bool $secure;

    private int $maxAge;
    private string $expires;

    private string $domain;
    private string $path;
    private string $sameSite;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public static function make(string $key, string $value): self
    {
        return new self($key, $value);
    }

    public function setExpires(string|Carbon $expires): self
    {
        if ($expires instanceof Carbon) {
            $expires = $expires->toString();
        }

        $this->expires = $expires;

        return $this;
    }

    public function setMaxAge(int $maxAge): self
    {
        $this->maxAge = $maxAge > 0 ? $maxAge : 1;

        return $this;
    }

    public function setHttpOnly(bool $httpOnly = true): self
    {
        $this->httpOnly = $httpOnly;

        return $this;
    }

    public function setSecure(bool $secure = true): self
    {
        $this->secure = $secure;

        return $this;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function setSameSite(string $sameSite): self
    {
        $this->sameSite = $sameSite;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getRawCookie(): string
    {
        $cookieString = $this->key . '=' . $this->value;

        if (isset($this->expires)) {
            $cookieString .= '; Expires=' . $this->expires;
        } elseif (isset($this->maxAge)) {
            $cookieString .= '; Max-Age=' . $this->maxAge;
        }

        if (isset($this->httpOnly) && $this->httpOnly === true) {
            $cookieString .= '; HttpOnly';
        }

        if (isset($this->secure) && $this->secure === true) {
            $cookieString .= '; Secure';
        }

        if (isset($this->domain)) {
            $cookieString .= '; Domain="' . $this->domain;
        }

        if (isset($this->path)) {
            $cookieString .= '; Path=' . $this->path;
        }

        if (isset($this->sameSite)) {
            $cookieString .= '; SameSite=' . $this->sameSite;
        }

        return $cookieString;
    }
}
