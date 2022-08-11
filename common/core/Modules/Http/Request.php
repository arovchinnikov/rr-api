<?php

declare(strict_types=1);

namespace Core\Modules\Http;

use Core\Modules\Http\Enums\RequestMethod;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

class Request
{
    public readonly array $get;
    public readonly array $post;

    public readonly array $headers;
    public readonly array $files;
    public readonly array $cookies;

    public readonly string $url;
    public readonly RequestMethod $method;

    public function __construct(ServerRequestInterface|RequestInterface $request)
    {
        $this->post = $request->getParsedBody() ?? [];
        $this->get = $request->getQueryParams();

        $this->headers = $this->prepareHeaders($request->getHeaders());
        $this->files = $this->prepareFiles($request->getUploadedFiles());
        $this->cookies = $request->getCookieParams();

        $this->url = $this->prepareUrl($request->getUri());
        $this->method = RequestMethod::from($request->getMethod());
    }

    private function prepareFiles(array $files): array
    {
        $preparedFiles = [];

        /** @var UploadedFileInterface $file */
        foreach ($files as $file) {
            $preparedFiles[] = new LoadedFile($file);
        }

        return $preparedFiles;
    }

    private function prepareUrl(UriInterface $uri): string
    {
        $url = $uri->getPath();
        $uriLen = strlen($url);

        if ($url[$uriLen - 1] === '/' && $uriLen !== 1) {
            $url = substr($url, 0, -1);
        }

        return $url;
    }

    private function prepareHeaders(array $headers): array
    {
        $preparedHeaders = [];
        foreach ($headers as $name => $value) {
            if (count($value) === 1) {
                $preparedHeaders[$name] = $value[0];
                continue;
            }

            $preparedHeaders[$name] = $value;
        }

        return $preparedHeaders;
    }
}
