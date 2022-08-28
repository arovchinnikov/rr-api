<?php

declare(strict_types=1);

namespace Core\Modules\Http;

use Psr\Http\Message\UploadedFileInterface;

class LoadedFile
{
    private string $filename;
    private string $mediaType;
    private string $content;
    private int $size;

    public function __construct(UploadedFileInterface $uploadedFile)
    {
        $this->filename = $uploadedFile->getClientFilename();
        $this->mediaType = $uploadedFile->getClientMediaType();
        $this->content = $uploadedFile->getStream()->getContents();
        $this->size = $uploadedFile->getSize();
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
