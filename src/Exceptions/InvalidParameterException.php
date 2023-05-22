<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Exceptions;

/**
 * The InvalidParameterException class is a custom exception class that extends the base \Exception class.
 * It is used to handle exceptions related to invalid parameters passed into a function or method.
 */
class InvalidParameterException extends \Exception
{
    public function __construct(string $param, string $type, string $className)
    {
        parent::__construct(sprintf('%s: Invalid param [%s]:[%s] in %s.', self::class, $param, $type, $className));
    }
}
