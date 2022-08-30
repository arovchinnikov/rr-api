<?php

declare(strict_types=1);

namespace Core\Components\RoadRunner\Exceptions;

class RoadRunnerException extends \Core\Exceptions\CoreException
{
    /**
     * @throws RoadRunnerException
     */
    public static function statusCodeTypeError(): void
    {
        throw new self('Status code has to be an integer');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidStatusCodeValue(int $code): void
    {
        throw new self(
            'Status code has to be an integer between 100 and 599. A status code of ' . $code . ' was given'
        );
    }

    /**
     * @throws RoadRunnerException
     */
    public static function withParsedBodyError(): void
    {
        throw new self('Data must be an object, array or null was given');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function streamCreateError(): void
    {
        throw new self('Body must be a string, resource or StreamInterface');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function detachedStream(): void
    {
        throw new self('Stream is detached');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function determineStreamPositionError(string $message): void
    {
        throw new self('Unable to determine stream position: ' . $message);
    }

    /**
     * @throws RoadRunnerException
     */
    public static function notSeekableStream(): void
    {
        throw new self('Stream is not seekable');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function seekStreamPositionError(string $offset, ?string $whence): void
    {
        throw new self(
            'Unable to seek to stream position "' . $offset . '" with ' . $whence
        );
    }

    /**
     * @throws RoadRunnerException
     */
    public static function writeStreamError(string $message): void
    {
        throw new self('Unable to write to stream: ' . $message);
    }

    /**
     * @throws RoadRunnerException
     */
    public static function readStreamError(string $message): void
    {
        throw new self('Unable to read from stream: ' . $message);
    }

    /**
     * @throws RoadRunnerException
     */
    public static function readStreamContentError(string $message): void
    {
        throw new self('Unable to read stream contents: ' . $message);
    }

    /**
     * @throws RoadRunnerException
     */
    public static function notReadableStream(): void
    {
        throw new self('Cannot read from non-readable stream');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function notWritableStream(): void
    {
        throw new self('Cannot read from non-writable stream');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidFilename(): void
    {
        throw new self('Upload file client filename must be a string or null');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidMediaType(): void
    {
        throw new self('Upload file client media type must be a string or null');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidProvidedUploadedFileContent(): void
    {
        throw new self('Invalid stream or file provided for UploadedFile');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidErrorStatus(): void
    {
        throw new self('Upload file error status must be an integer and one of the "UPLOAD_ERR_*" const');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function fileUploadError(): void
    {
        throw new self('Cannot retrieve stream due to upload error');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function movedStream(): void
    {
        throw new self('Cannot retrieve stream after it has already been moved');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function fileCantBeOpened(string $file, string $errorMessage): void
    {
        throw new self('The file "' . $file . '" cannot be opened: ' . $errorMessage);
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidPathProvided(): void
    {
        throw new self('Invalid path provided for move operation, it must be a non-empty string');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidMovePath(string $targetPath, string $errorMessage): void
    {
        throw new self('Uploaded file could not be moved to "' . $targetPath . '": ' . $errorMessage);
    }

    /**
     * @throws RoadRunnerException
     */
    public static function schemeTypeError(): void
    {
        throw new self('Scheme must be a string');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function hostTypeError(): void
    {
        throw new self('Host must be a string');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function pathTypeError(): void
    {
        throw new self('Path must be a string');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function queryTypeError(): void
    {
        throw new self('Query and fragment must be a string');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function methodTypeError(): void
    {
        throw new self('Method must be a string');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidRequestTarget(): void
    {
        throw new self('Invalid request target provided, it cannot contain whitespace');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidPortError(int $port): void
    {
        throw new self('Invalid port: ' . $port . '. Must be between 0 and 65535');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidHeaderName(): void
    {
        throw new self('Header name must be an RFC 7230 compatible string');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function invalidHeaderValues(): void
    {
        throw new self('Header values must be RFC 7230 compatible strings');
    }

    /**
     * @throws RoadRunnerException
     */
    public static function headerValuesIsEmpty(): void
    {
        throw new self('Header values must be a string or an array of strings, empty array given');
    }
}
