<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Exceptions\FileNotFoundException;
use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 * The JsonLinesValidator class is used to validate, extract, and create JSON Lines formatted data.
 * JSON Lines is a convenient format for storing structured data that may
 * be processed one record at a time. It works well with logs and any other time-series data.
 */
class JsonLinesValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'jsonlines';

    /**
     * @var string
     */
    public const EXTENSION = 'jsonlines';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! is_string($value) || ! isset($config['type']) || $config['type'] !== self::NAME) {
            return false;
        }

        if (substr($value, -5) === self::EXTENSION && ! file_exists($value)) {
            throw new FileNotFoundException($value, self::class);
        }

        $content = null;

        if (file_exists($value)) {
            $content = file_get_contents($value);
        } elseif (substr($value, -5) !== self::EXTENSION) {
            $content = $value;
        }

        if (null === $content) {
            return false;
        }

        return ! empty(self::extract($content));
    }

    /**
     * Extracts the given value.
     *
     * @param      mixed  $value
     *
     * @return     array<mixed>
     */
    public static function extract(mixed $value): array
    {
        if (! is_string($value)) {
            return [];
        }

        return array_filter(array_map(function ($data) {
            return json_decode($data, true) ?? [];
        }, array_filter(explode(PHP_EOL, $value), function ($ligne) {
            return is_string($ligne) && ! empty(trim($ligne));
        })), function ($data) {
            return ! empty($data);
        });
    }

    /**
     * @param      array<mixed>   $value
     *
     * @return     string
     */
    public static function create(array $value): string
    {
        $data = [];

        foreach ($value as $key => $val) {
            if (! empty($val)) {
                $data[] = json_encode($val);
            }
        }

        return implode(PHP_EOL, $data);
    }
}
