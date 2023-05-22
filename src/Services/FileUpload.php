<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Validators\JsonLinesValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;

/**
 * Upload a file that contains document(s) to be used across various endpoints/features.
 * Currently, the size of all the files uploaded by one organization can be up to 1 GB.
 * Please contact OpenAi if you need to increase the storage limit.
 */
final class FileUpload extends AbstractService
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
    public const OPENAI_ENDPOINT = '/files';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PATH_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [
        /**
         * file string Required
         *
         * Name of the JSON Lines file to be uploaded.
         *
         * If the purpose is set to "fine-tune", each line is a JSON
         * record with "prompt" and "completion" fields representing your training examples.
         */
        [
            'name' => 'file', RequiredValidator::NAME => true, 'type' => JsonLinesValidator::NAME,
        ],

        /**
         * purpose string Required
         *
         * The intended purpose of the uploaded documents.
         * Use "fine-tune" for Fine-tuning. This allows us to validate the format of the uploaded file.
         */
        [
            'name' => 'purpose', RequiredValidator::NAME => true, 'type' => StringValidator::NAME,
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_METHOD_TYPE = 'FILE';
}
