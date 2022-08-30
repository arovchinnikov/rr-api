<?php

declare(strict_types=1);

namespace Core\Components\Http;

use Core\Components\Http\Enums\RequestMethod;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;

class Request
{
    public readonly array $get;
    public readonly array $post;

    public readonly array $headers;
    public readonly array $files;
    /** @var Cookie[] */
    public readonly array $cookies;
    public readonly string $url;
    public readonly RequestMethod $method;
    public readonly array $urlParams;

    public function __construct(ServerRequestInterface $request)
    {
        $this->get = $request->getQueryParams();
        $this->post = $this->prepareBody($request);

        $this->headers = $this->prepareHeaders($request->getHeaders());
        $this->files = $this->prepareFiles($request->getUploadedFiles());
        $this->cookies = $this->prepareCookies($request->getCookieParams());

        $this->url = $this->prepareUrl($request->getUri());
        $this->method = RequestMethod::from($request->getMethod());
    }

    public function initUrlParams(array $urlParams): array
    {
        $preparedParams = [];
        foreach ($urlParams as $key => $param) {
            if (intval($param) == $param) {
                $param = +$param;
            }

            $preparedParams[$key] = $param;
        }

        return $this->urlParams = $preparedParams;
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

    private function prepareCookies(array $cookies): array
    {
        $preparedCookies = [];
        foreach ($cookies as $key => $value) {
            $preparedCookies[$key] = new Cookie($key, $value);
        }

        return $preparedCookies;
    }

    private function prepareBody(ServerRequestInterface $request): array
    {
        if ($request->getHeaderLine('content-type') === 'application/json') {
            $request->getBody()->rewind();
            return json_decode($request->getBody()->getContents(), true) ?? [];
        }

        return $request->getParsedBody() ?? [];
    }
}
