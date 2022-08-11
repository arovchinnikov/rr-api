<?php

declare(strict_types=1);

namespace Core\Modules\RoadRunner;

use Core\Base\Interfaces\Types\ToArray;
use Core\Modules\Http\Enums\ResponseCode;
use Core\Modules\RoadRunner\Components\Request;
use Core\Modules\RoadRunner\Components\Response;
use Core\Modules\RoadRunner\Components\ServerRequest;
use Core\Modules\RoadRunner\Components\Stream;
use Core\Modules\RoadRunner\Components\UploadedFile;
use Core\Modules\RoadRunner\Components\Uri;
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

        return new Response($code, [], null, '1.1', $reasonPhrase);
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
    public function createJsonResponse(array|ToArray $body, ResponseCode $responseCode = null): ResponseInterface
    {
        $response = $this
            ->createResponse()
            ->withStatus($responseCode->value)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($body));

        return $response;
    }
}