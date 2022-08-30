<?php

declare(strict_types=1);

namespace Core\Components\RoadRunner;

use BackedEnum;
use Core\Base\DataValues\Interfaces\BaseValue;
use Core\Base\Interfaces\Types\ToArray;
use Core\Components\Http\Enums\ResponseCode;
use Core\Components\Http\Response;
use Core\Components\RoadRunner\Components\Request;
use Core\Components\RoadRunner\Components\ServerResponse;
use Core\Components\RoadRunner\Components\ServerRequest;
use Core\Components\RoadRunner\Components\Stream;
use Core\Components\RoadRunner\Components\UploadedFile;
use Core\Components\RoadRunner\Components\Uri;
use InvalidArgumentException;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;

class HttpFactory implements
    RequestFactoryInterface,
    ResponseFactoryInterface,
    ServerRequestFactoryInterface,
    StreamFactoryInterface,
    UploadedFileFactoryInterface,
    UriFactoryInterface
{
    public function createRequest(string $method, mixed $uri): RequestInterface
    {
        return new Request($method, $uri);
    }

    /**
     * @throws Exceptions\RoadRunnerException
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        if (func_num_args() < 2) {
            $reasonPhrase = null;
        }

        return new ServerResponse($code, [], null, '1.1', $reasonPhrase);
    }

    /**
     * @throws Exceptions\RoadRunnerException
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return Stream::create($content);
    }

    /**
     * @throws Exceptions\RoadRunnerException
     */
    public function createStreamFromResource(mixed $resource): StreamInterface
    {
        return Stream::create($resource);
    }

    /**
     * @throws Exceptions\RoadRunnerException
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        if ('' === $filename) {
            throw new RuntimeException('Path cannot be empty');
        }

        if (false === $resource = @\fopen($filename, $mode)) {
            if ('' === $mode || false === in_array($mode[0], ['r', 'w', 'a', 'x', 'c'], true)) {
                throw new InvalidArgumentException('The mode "' . $mode . '" is invalid.');
            }

            throw new RuntimeException(
                'The file "' . $filename . '" cannot be opened: ' . error_get_last()['message'] ?? ''
            );
        }

        return Stream::create($resource);
    }

    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }

    /**
     * @throws Exceptions\RoadRunnerException
     */
    public function createUploadedFile(
        StreamInterface $stream,
        int $size = null,
        int $error = UPLOAD_ERR_OK,
        string $clientFilename = null,
        string $clientMediaType = null
    ): UploadedFileInterface {
        if (null === $size) {
            $size = $stream->getSize();
        }

        return new UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }

    /**
     * @throws Exceptions\RoadRunnerException
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new ServerRequest($method, $uri, [], null, '1.1', $serverParams);
    }

    /**
     * @throws Exceptions\RoadRunnerException
     */
    public function createJsonResponse(array|ToArray $content, Response $response): ResponseInterface
    {
        $serverResponse = $this
            ->createResponse()
            ->withStatus($response->getCode()->value)
            ->withHeader('Content-Type', 'application/json');

        $body = $this->prepareContent($content);

        if (!empty($body)) {
            $serverResponse->withHeader('Content-Type', 'application/json');
            $serverResponse->getBody()->write(json_encode($body));
        }

        foreach ($response->getCookies() as $cookie) {
            $serverResponse = $serverResponse->withHeader('Set-Cookie', $cookie->getRawCookie());
        }

        return $serverResponse;
    }

    /**
     * @throws Exceptions\RoadRunnerException
     */
    public function createErrorResponse(?array $body, int $code): ResponseInterface
    {
        $serverResponse = $this
            ->createResponse()
            ->withStatus($code)
            ->withHeader('Content-Type', 'application/json');

        $serverResponse->getBody()->write(json_encode($body));
        return $serverResponse;
    }

    private function prepareContent(array|ToArray $values): array
    {
        if ($values instanceof ToArray) {
            $values = $values->toArray();
        }

        $return = [];
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $return[$key] = $this->prepareContent($value);
                continue;
            }

            if (!is_object($value)) {
                $return[$key] = $value;
                continue;
            }

            if ($value instanceof ToArray) {
                $return[$key] = $this->prepareContent($value->toArray());
                continue;
            }

            if ($value instanceof BaseValue) {
                $return[$key] = $value->getValue();
                continue;
            }

            if ($value instanceof BackedEnum) {
                $return[$key] = $value->value;
                continue;
            }

            return [];
        }

        return $return;
    }
}
