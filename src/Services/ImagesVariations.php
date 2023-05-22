<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Validators\AnyValidator;
use MounirRquiba\OpenAi\Validators\EnumValidator;
use MounirRquiba\OpenAi\Validators\IntegerValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;

/**
 * Given a prompt and/or an input image, the model will generate a new image.
 *
 * Related guide: https://platform.openai.com/docs/guides/images
 *
 * Creates a variation of a given image.
 */
final class ImagesVariations extends AbstractService
{
    /**
     * {@inheritdoc}
     */
    public const OPENAI_DECODE_RESPONSE = true;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ENDPOINT = '/images/variations';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ID_NAME = null;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [
        /**
         * prompt string Required
         *
         * The image to edit. Must be a valid PNG file, less than 4MB, and square.
         * If mask is not provided, image must have transparency, which will be used as the mask.
         *
         */
        ['name' => 'image', RequiredValidator::NAME => true, 'type' => AnyValidator::NAME],

        /**
         * n integer Optional Defaults to 1
         *
         * The number of images to generate. Must be between 1 and 10.
         */
        ['name' => 'n', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME, 'max' => 10, 'min' => 1],

        /**
         * size string Optional Defaults to 1024x1024
         *
         * The size of the generated images. Must be one of 256x256, 512x512, or 1024x1024.
         */
        ['name' => 'size', RequiredValidator::NAME => false, 'type' => EnumValidator::NAME, 'choices' => ['256x256', '512x512', '1024x1024']],

        /**
         * response_format string Optional Defaults to url
         *
         * The format in which the generated images are returned. Must be one of url or b64_json.
         */
        ['name' => 'response_format', RequiredValidator::NAME => false, 'type' => EnumValidator::NAME, 'choices' => ['url', 'b64_json']],

        /**
         * user string Optional
         *
         * A unique identifier representing your end-user, which can help OpenAI to monitor and detect abuse.
         *
         * Details: https://platform.openai.com/docs/guides/safety-best-practices/end-user-ids
         *
         */
        ['name' => 'user', RequiredValidator::NAME => false, 'type' => StringValidator::NAME, 'max' => 100, 'min' => 1],
    ];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PATH_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_METHOD_TYPE = 'FILE';
}
