<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * The AnyValidator class can be used when you need a validator that
 * does not impose any specific conditions on the configuration and
 * value that it is validating, it just checks if the 'type' of configuration is 'any'.
 */
class AnyValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'any';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! isset($config['type']) || self::NAME !== $config['type']) {
            return false;
        }

        return true;
    }
}
