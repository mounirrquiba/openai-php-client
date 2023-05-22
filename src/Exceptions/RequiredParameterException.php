<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Exceptions;

/**
 * The RequiredParameterException class is a custom exception that extends the base \Exception class.
 * It is used to handle exceptions related to required parameters not being provided or being provided with null or empty values in a function or method.
 */
class RequiredParameterException extends \Exception
{
    public function __construct(string $param, string $type, string $className)
    {
        parent::__construct(sprintf('%s: Required param [%s]:[%s] in %s.', self::class, $param, $type, $className));
    }
}
