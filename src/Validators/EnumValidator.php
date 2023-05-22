<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * The EnumValidator class checks if a given input is one of a set of specified choices.
 * It's particularly useful for validating values against a defined set of options or an 'enumeration'.
 */
class EnumValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'enum';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! isset($config['type']) || self::NAME !== $config['type']) {
            return false;
        }

        if (! isset($config['choices']) || ! is_array($config['choices']) || empty($config['choices'])) {
            return false;
        }

        if (! in_array($value, $config['choices'], true)) {
            return false;
        }

        return true;
    }
}
