<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Validators\EnumValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringOrArrayValidator;

/**
 * Given a input text, outputs if the model classifies it as violating OpenAI's content policy.
 *
 * Related guide: https://platform.openai.com/docs/guides/moderation
 */
final class Moderations extends AbstractService
{
    /**
     * {@inheritdoc}
     */
    public const OPENAI_DECODE_RESPONSE = true;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ID_NAME = null;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ENDPOINT = '/moderations';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [
        /**
         * input string or array Required
         *
         * The input text to classify
         */
        [
            'name' => 'input',  RequiredValidator::NAME => true, 'type' => StringOrArrayValidator::NAME,
        ],

        /**
         * input string Optional Defaults to 'text-moderation-stable'
         *
         * The input text to use as a starting point for the edit.
         */
        [
            'name' => 'model', RequiredValidator::NAME => false, 'type' => EnumValidator::NAME,
            'choices' => [' text-moderation-latest', 'text-moderation-stable'], 'default' => 'text-moderation-latest',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PATH_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_METHOD_TYPE = 'POST';
}
