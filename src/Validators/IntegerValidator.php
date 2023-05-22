<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * Tthe IntegerValidator class validates whether a given input is an integer
 * and checks if it is within the specified range.
 * This is particularly useful when dealing with integer parameters that need to be within certain bounds.
 */
class IntegerValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'integer';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! is_int($value) || ! isset($config['type']) || self::NAME !== $config['type']) {
            return false;
        }

        if (isset($config['max']) && $value > $config['max']) {
            return false;
        }

        if (isset($config['min']) && $value < $config['min']) {
            return false;
        }

        return true;
    }
}
