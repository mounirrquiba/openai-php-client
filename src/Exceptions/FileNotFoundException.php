<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Exceptions;

/**
 * The FileNotFoundException class is a custom exception class that extends the base \Exception class.
 * It is used to handle exceptions related to files that are not found or inaccessible.
 */
class FileNotFoundException extends \Exception
{
    public function __construct(string $message, string $className)
    {
        parent::__construct(sprintf('%s: %s in %s', self::class, $message, $className));
    }
}
