<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;

/**
 * List and describe the various models available in the API.
 * You can refer to the Models documentation to understand what models are available and the differences between them.
 *
 * Documentation: https://platform.openai.com/docs/models
 *
 * Lists the currently available models, and provides basic information about each one such as the owner and availability.
 */
final class Models extends AbstractService
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
    public const OPENAI_ENDPOINT = '/models';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PATH_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_METHOD_TYPE = 'GET';
}
