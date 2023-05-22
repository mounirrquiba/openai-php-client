<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * The RequiredValidator class is used to validate whether
 * a given value is present or not, as per the requirements specified in the configuration.
 */
class RequiredValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'required';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! isset($config[self::NAME]) || ! $config[self::NAME]) {
            return true;
        }

        // if ($config[self::NAME] && (is_string($value) || is_array($value) || is_bool($value))) {
        // 	return true;
        // }

        if (! empty($value) && (is_string($value) || is_array($value) || is_bool($value))) {
            return true;
        }

        return false;
    }
}
