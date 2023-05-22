<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * This class is responsible for validating if a given value is a string according to the given configuration.
 */
class StringValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'string';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! is_string($value) || ! isset($config['type']) || ! in_array($config['type'], [self::NAME, StringOrArrayValidator::NAME])) {
            return false;
        }

        if (! isset($config['max'])) {
            $config['max'] = 1000;
        }

        if (strlen($value) > $config['max']) {
            return false;
        }

        return true;
    }
}
