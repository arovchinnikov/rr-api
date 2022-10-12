<?php

declare(strict_types=1);

namespace Core\Components\RoadRunner\Components\Traits;

use Core\Components\RoadRunner\Components\Stream;
use Core\Components\RoadRunner\Exceptions\RoadRunnerException;
use Psr\Http\Message\StreamInterface;

trait MessageTrait
{
    private array $headers = [];
    private array $headerNames = [];
    private string $protocol = '1.1';
    private ?StreamInterface $stream;

    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    public function withProtocolVersion($version): self
    {
        if ($this->protocol === $version) {
            return $this;
        }

        $new = clone $this;
        $new->protocol = $version;

        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headerNames[strtr($name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')]);
    }

    public function getHeader($name): array
    {
        $name = strtr($name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
        if (!isset($this->headerNames[$name])) {
            return [];
        }

        $name = $this->headerNames[$name];

        return $this->headers[$name];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    /**
     * @throws RoadRunnerException
     */
    public function withHeader($name, $value): self
    {
        $value = $this->validateAndTrimHeader($name, $value);
        $normalized = strtr($name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');

        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $name;
        $new->headers[$name] = $value;

        return $new;
    }

    /**
     * @throws RoadRunnerException
     */
    public function withAddedHeader($name, $value): self
    {
        if (!is_string($name) || '' === $name) {
            RoadRunnerException::invalidHeaderName();
        }

        $new = clone $this;
        $new->setHeaders([$name => $value]);

        return $new;
    }

    public function withoutHeader($name): self
    {
        $normalized = strtr($name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }

        $name = $this->headerNames[$normalized];
        $new = clone $this;
        unset($new->headers[$name], $new->headerNames[$normalized]);

        return $new;
    }

    /**
     * @throws RoadRunnerException
     */
    public function getBody(): StreamInterface
    {
        if (empty($this->stream)) {
            $this->stream = Stream::create('');
        }

        return $this->stream;
    }

    public function withBody(StreamInterface $body): self
    {
        if (!empty($this->stream) && $body === $this->stream) {
            return $this;
        }

        $new = clone $this;
        $new->stream = $body;

        return $new;
    }

    /**
     * @throws RoadRunnerException
     */
    private function setHeaders(array $headers): void
    {
        foreach ($headers as $header => $value) {
            if (is_int($header)) {
                $header = (string) $header;
            }
            $value = $this->validateAndTrimHeader($header, $value);
            $normalized = strtr($header, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
            if (isset($this->headerNames[$normalized])) {
                $header = $this->headerNames[$normalized];
                $this->headers[$header] = array_merge($this->headers[$header], $value);
            } else {
                $this->headerNames[$normalized] = $header;
                $this->headers[$header] = $value;
            }
        }
    }

    /**
     * @throws RoadRunnerException
     */
    private function validateAndTrimHeader(string $name, array|string $values): array
    {
        if (1 !== preg_match("@^[!#$%&'*+.^_`|~0-9A-Za-z-]+$@", $name)) {
            RoadRunnerException::invalidHeaderName();
        }

        if (!is_array($values)) {
            if (
                (!is_numeric($values) && !is_string($values))
                || 1 !== preg_match("@^[ \t\x21-\x7E\x80-\xFF]*$@", (string) $values)
            ) {
                RoadRunnerException::invalidHeaderValues();
            }

            return [trim((string) $values, " \t")];
        }

        if (empty($values)) {
            RoadRunnerException::headerValuesIsEmpty();
        }

        $returnValues = [];
        foreach ($values as $v) {
            if (
                (!is_numeric($v) && !is_string($v))
                || 1 !== preg_match("@^[ \t\x21-\x7E\x80-\xFF]*$@", (string) $v)
            ) {
                RoadRunnerException::invalidHeaderValues();
            }

            $returnValues[] = trim((string) $v, " \t");
        }

        return $returnValues;
    }
}
