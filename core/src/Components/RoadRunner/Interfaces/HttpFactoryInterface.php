<?php

declare(strict_types=1);

namespace Core\Components\RoadRunner\Interfaces;

use Core\Base\Interfaces\Types\ToArray;
use Core\Components\Http\Response;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

interface HttpFactoryInterface extends
    RequestFactoryInterface,
    ResponseFactoryInterface,
    ServerRequestFactoryInterface,
    StreamFactoryInterface,
    UploadedFileFactoryInterface,
    UriFactoryInterface
{
    public function createErrorResponse(?array $body, int $code): ResponseInterface;

    public function createJsonResponse(array|ToArray $content, Response $response): ResponseInterface;
}
