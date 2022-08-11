<?php

declare(strict_types=1);

namespace Core\Modules\Http;

use Core\Modules\Http\Enums\ResponseCode;

class Response
{
    private ResponseCode $responseCode;
    /** @var Cookie[] */
    private array $cookies = [];

    public function setCode(ResponseCode $responseCode): void
    {
        $this->responseCode = $responseCode;
    }

    public function getCode(): ResponseCode
    {
        return $this->responseCode;
    }

    public function setCookie(Cookie $cookie): void
    {
        $this->cookies[] = $cookie;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }
}
