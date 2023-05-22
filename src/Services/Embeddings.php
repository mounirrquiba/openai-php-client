<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringOrArrayValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;

/**
 * Get a vector representation of a given input that can be easily consumed by machine learning models and algorithms.
 *
 * Related guide: https://platform.openai.com/docs/guides/embeddings
 *
 * Creates an embedding vector representing the input text.
 */
final class Embeddings extends AbstractService
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
    public const OPENAI_ENDPOINT = '/embeddings';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [
        /**
         * model string Required
         *
         * ID of the model to use. You can use the text-davinci-edit-001 or code-davinci-edit-001 model with this endpoint.
         *
         */
        ['name' => 'model', RequiredValidator::NAME => true, 'type' => StringValidator::NAME, 'default' => 'text-embedding-ada-002'],

        /**
         * input string or array Required
         *
         * Input text to get embeddings for, encoded as a string or array of tokens.
         * To get embeddings for multiple inputs in a single request, pass an array of strings or array of token arrays.
         * Each input must not exceed 8192 tokens in length.
         */
        ['name' => 'input', RequiredValidator::NAME => true, 'type' => StringOrArrayValidator::NAME],

        /**
         *	user string Optional
         *
         *	A unique identifier representing your end-user, which can help OpenAI to monitor and detect abuse.
         *
         *  Details: https://platform.openai.com/docs/guides/safety-best-practices/end-user-ids
         */
        ['name' => 'user', RequiredValidator::NAME => false, 'type' => StringValidator::NAME],
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
