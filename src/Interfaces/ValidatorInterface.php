<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Interfaces;

/**
 * The ValidatorInterface defines a contract for creating a validation class.
 * It enforces any class implementing it to have a isValid method.
 */
interface ValidatorInterface
{
    /**
     * Check if value is valid from validators config
     *
     * @param array<mixed> $config
     * @param mixed|array $value
     *
     * @return bool
     */
    public static function isValid(array $config, mixed $value): bool;
}
