<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Validators;

use MounirRquiba\OpenAi\Interfaces\ValidatorInterface;

/**
 *  The MessagesValidator class is used to validate if a given array
 *  of messages is valid according to a specified configuration.
 */
class MessagesValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public const NAME = 'messages';

    /**
     * {@inheritdoc}
     */
    public static function isValid(array $config, mixed $value): bool
    {
        if (! isset($config['type']) || self::NAME !== $config['type']) {
            return false;
        }

        if (! is_array($value) || empty($value)) {
            return false;
        }

        $isValid = true;

        foreach ($config['fields'] as $conf) {
            foreach ($value as $key => $val) {
                $content = $val[$conf['name']] ?? null;

                if (! RequiredValidator::isValid($conf, $content)) {
                    $isValid = false;

                    break 2;
                }

                if ($content) {
                    switch ($conf['type']) {
                        case 'string':
                            if (! StringValidator::isValid($conf, $content)) {
                                $isValid = false;

                                break 3;
                            }

                            break;

                        case 'enum':
                            if (! EnumValidator::isValid($conf, $content)) {
                                $isValid = false;

                                break 3;
                            }

                            break;
                    }
                }
            }
        }

        return $isValid;
    }
}
