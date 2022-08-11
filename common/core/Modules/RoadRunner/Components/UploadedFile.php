<?php

declare(strict_types=1);

namespace Core\Modules\RoadRunner\Components;

use Core\Modules\RoadRunner\Exceptions\RoadRunnerException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface
{
    private const ERRORS = [
        UPLOAD_ERR_OK => 1,
        UPLOAD_ERR_INI_SIZE => 1,
        UPLOAD_ERR_FORM_SIZE => 1,
        UPLOAD_ERR_PARTIAL => 1,
        UPLOAD_ERR_NO_FILE => 1,
        UPLOAD_ERR_NO_TMP_DIR => 1,
        UPLOAD_ERR_CANT_WRITE => 1,
        UPLOAD_ERR_EXTENSION => 1,
    ];

    private string $clientFilename;
    private string $clientMediaType;
    private int $error;
    private ?string $file;
    private bool $moved = false;
    private int $size;
    private ?StreamInterface $stream;

    /**
     * @throws RoadRunnerException
     */
    public function __construct(
        mixed $streamOrFile,
        int $size,
        int $errorStatus,
        string $clientFilename = null,
        string $clientMediaType = null
    ) {
        if (!isset(self::ERRORS[$errorStatus])) {
            RoadRunnerException::invalidErrorStatus();
        }

        if (null !== $clientFilename && !is_string($clientFilename)) {
            RoadRunnerException::invalidFilename();
        }

        if (null !== $clientMediaType && !is_string($clientMediaType)) {
            RoadRunnerException::invalidMediaType();
        }

        $this->error = $errorStatus;
        $this->size = $size;
        $this->clientFilename = $clientFilename;
        $this->clientMediaType = $clientMediaType;

        if (UPLOAD_ERR_OK === $this->error) {
            if (is_string($streamOrFile) && '' !== $streamOrFile) {
                $this->file = $streamOrFile;
            } elseif (is_resource($streamOrFile)) {
                $this->stream = Stream::create($streamOrFile);
            } elseif ($streamOrFile instanceof StreamInterface) {
                $this->stream = $streamOrFile;
            } else {
                RoadRunnerException::invalidProvidedUploadedFileContent();
            }
        }
    }

    /**
     * @throws RoadRunnerException
     */
    private function validateActive(): void
    {
        if (UPLOAD_ERR_OK !== $this->error) {
            RoadRunnerException::fileUploadError();
        }

        if ($this->moved) {
            RoadRunnerException::movedStream();
        }
    }

    /**
     * @throws RoadRunnerException
     */
    public function getStream(): StreamInterface
    {
        $this->validateActive();

        if ($this->stream instanceof StreamInterface) {
            return $this->stream;
        }

        if (false === $resource = @fopen($this->file, 'r')) {
            RoadRunnerException::fileCantBeOpened($this->file, error_get_last()['message'] ?? '');
        }

        return Stream::create($resource);
    }

    /**
     * @throws RoadRunnerException
     */
    public function moveTo(mixed $targetPath): void
    {
        $this->validateActive();

        if (!is_string($targetPath) || '' === $targetPath) {
            RoadRunnerException::invalidPathProvided();
        }

        if (null !== $this->file) {
            $this->moved = 'cli' === PHP_SAPI ?
                @rename($this->file, $targetPath) : @move_uploaded_file($this->file, $targetPath);

            if (false === $this->moved) {
                RoadRunnerException::invalidMovePath($targetPath, error_get_last()['message'] ?? '');
            }
        } else {
            $stream = $this->getStream();
            if ($stream->isSeekable()) {
                $stream->rewind();
            }

            if (false === $resource = @fopen($targetPath, 'w')) {
                RoadRunnerException::fileCantBeOpened($targetPath, error_get_last()['message'] ?? '');
            }

            $dest = Stream::create($resource);

            while (!$stream->eof()) {
                if (!$dest->write($stream->read(1048576))) {
                    break;
                }
            }

            $this->moved = true;
        }
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }
}
