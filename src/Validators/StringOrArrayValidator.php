<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * The StringOrArrayValidator class is used to validate whether
 * a given value is either a string or an array, according to the specified configuration.
 */
class StringOrArrayValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'stringorarray';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! isset($config['type']) || self::NAME !== $config['type']) {
            return false;
        }

        return StringValidator::isValid($config, $value) || ArrayValidator::isValid($config, $value);
    }
}
