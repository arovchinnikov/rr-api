<?php

declare(strict_types=1);

namespace Core\Components\RoadRunner\Components\Traits;

use Core\Components\RoadRunner\Exceptions\RoadRunnerException;
use Psr\Http\Message\UriInterface;

trait RequestTrait
{
    private string $method;
    private ?string $requestTarget;
    private ?UriInterface $uri;

    public function getRequestTarget(): string
    {
        if (!empty($this->requestTarget)) {
            return $this->requestTarget;
        }

        if ('' === $target = $this->uri->getPath()) {
            $target = '/';
        }
        if ('' !== $this->uri->getQuery()) {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    /**
     * @throws RoadRunnerException
     */
    public function withRequestTarget($requestTarget): self
    {
        if (preg_match('#\s#', $requestTarget)) {
            RoadRunnerException::invalidRequestTarget();
        }

        $new = clone $this;
        $new->requestTarget = $requestTarget;

        return $new;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @throws RoadRunnerException
     */
    public function withMethod($method): self
    {
        if (!is_string($method)) {
            RoadRunnerException::methodTypeError();
        }

        $new = clone $this;
        $new->method = $method;

        return $new;
    }

    public function getUri(): UriInterface|null
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): self
    {
        if ($uri === $this->uri) {
            return $this;
        }

        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost || !$this->hasHeader('Host')) {
            $new->updateHostFromUri();
        }

        return $new;
    }

    private function updateHostFromUri(): void
    {
        if ('' === $host = $this->uri->getHost()) {
            return;
        }

        if (null !== ($port = $this->uri->getPort())) {
            $host .= ':' . $port;
        }

        if (isset($this->headerNames['host'])) {
            $header = $this->headerNames['host'];
        } else {
            $this->headerNames['host'] = $header = 'Host';
        }

        $this->headers = [$header => [$host]] + $this->headers;
    }
}
