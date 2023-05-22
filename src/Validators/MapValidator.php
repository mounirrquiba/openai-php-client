<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * The MapValidator class is used to validate if a given value is a valid JSON object
 * (also often referred to as a 'map' or 'dictionary' in other languages), or null.
 */
class MapValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'map';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if ((! is_string($value) && ! is_null($value)) || ! isset($config['type']) || self::NAME !== $config['type']) {
            return false;
        }

        if (null === $value) {
            return true;
        }

        $json = @json_decode($value, true);

        if (! $json) {
            return false;
        }

        return true;
    }
}
