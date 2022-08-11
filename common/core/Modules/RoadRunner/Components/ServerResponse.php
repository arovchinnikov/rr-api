<?php

declare(strict_types=1);

namespace Core\Modules\RoadRunner\Components;

use Core\Modules\Http\Enums\ResponseCode;
use Core\Modules\RoadRunner\Components\Traits\MessageTrait;
use Core\Modules\RoadRunner\Exceptions\RoadRunnerException;
use Psr\Http\Message\ResponseInterface;

class ServerResponse implements ResponseInterface
{
    use MessageTrait;

    private string $reasonPhrase = '';
    private int $statusCode;

    /**
     * @throws RoadRunnerException
     */
    public function __construct(
        int $status = 200,
        array $headers = [],
        mixed $body = null,
        string $version = '1.1',
        string $reason = null
    ) {
        if ('' !== $body && null !== $body) {
            $this->stream = Stream::create($body);
        }

        $this->statusCode = $status;
        $this->setHeaders($headers);
        if (null === $reason && !empty(ResponseCode::from($status))) {
            $this->reasonPhrase = ResponseCode::from($status)->message();
        } else {
            $this->reasonPhrase = $reason ?? '';
        }

        $this->protocol = $version;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * @throws RoadRunnerException
     */
    public function withStatus($code, $reasonPhrase = ''): self
    {
        if (!is_int($code) && !is_string($code)) {
            RoadRunnerException::statusCodeTypeError();
        }

        $code = (int) $code;
        if ($code < 100 || $code > 599) {
            RoadRunnerException::invalidStatusCodeValue($code);
        }

        $new = clone $this;
        $new->statusCode = $code;
        if ((null === $reasonPhrase || '' === $reasonPhrase) && !empty(ResponseCode::from($new->statusCode))) {
            $reasonPhrase = ResponseCode::from($new->statusCode)->message();
        }
        $new->reasonPhrase = $reasonPhrase;

        return $new;
    }
}
