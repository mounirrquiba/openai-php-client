<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * The ArrayValidator class is used to validate whether a given input
 * is an array and does not exceed a specified maximum count.
 * The maximum count defaults to 4 if not otherwise specified.
 */
class ArrayValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'array';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! is_array($value) || ! isset($config['type']) || ! in_array($config['type'], [self::NAME, StringOrArrayValidator::NAME])) {
            return false;
        }

        if (! isset($config['maxcount'])) {
            $config['maxcount'] = 4;
        }

        if (count($value) > $config['maxcount']) {
            return false;
        }

        return true;
    }
}
