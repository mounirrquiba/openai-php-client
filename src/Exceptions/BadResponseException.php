<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Exceptions;

/**
 * The BadResponseException class is a custom exception class that extends the base \Exception class.
 * It is used to handle exceptions related to bad or unexpected responses.
 */
class BadResponseException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct(sprintf('%s: %s', self::class, $message));
    }
}
