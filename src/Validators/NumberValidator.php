<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * The NumberValidator class is designed to validate if a given value is a valid number within a specified range.
 */
class NumberValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'number';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! isset($config['type']) || self::NAME !== $config['type']) {
            return false;
        }

        if (! isset($config['max'])) {
            $config['max'] = 100000;
        }

        if (! isset($config['min'])) {
            $config['min'] = -100000;
        }

        if (! is_numeric($value) || $value < $config['min'] || $value > $config['max']) {
            return false;
        }

        return true;
    }
}
