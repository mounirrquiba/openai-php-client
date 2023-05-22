<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * The BooleanValidator class validates whether a given input
 * is a boolean type according to the criteria specified in the $config.
 */
class BooleanValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'boolean';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! isset($config['type']) || self::NAME !== $config['type']) {
            return false;
        }

        return is_bool($value);
    }
}
