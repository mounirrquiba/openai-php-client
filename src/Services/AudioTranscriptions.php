<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Validators\AnyValidator;
use MounirRquiba\OpenAi\Validators\EnumValidator;
use MounirRquiba\OpenAi\Validators\NumberValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;

/**
 * Learn how to turn audio into text.
 *
 * Related guide: https://platform.openai.com/docs/guides/speech-to-text
 *
 * Transcribes audio into the input language.
 */
final class AudioTranscriptions extends AbstractService
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
    public const OPENAI_ENDPOINT = '/audio/transcriptions';



    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [
        /**
         * file string Required
         *
         * The audio file to transcribe, in one of these formats: mp3, mp4, mpeg, mpga, m4a, wav, or webm.
         */
        [
            'name' => 'file', RequiredValidator::NAME => true, 'type' => AnyValidator::NAME,
        ],

        /**
         * model string Required
         *
         * ID of the model to use. Only whisper-1 is currently available.
         */
        [
            'name' => 'model', RequiredValidator::NAME => true,
            'type' => EnumValidator::NAME, 'choices' => ['whisper-1'],
        ],

        /**
         * prompt string Optional
         *
         * An optional text to guide the model's style or continue a previous audio segment. The prompt should be in English.
         */
        ['name' => 'prompt', RequiredValidator::NAME => false, 'type' => StringValidator::NAME],

        /**
         * response_format string Optional Defaults to json
         *
         * The format of the transcript output, in one of these options: json, text, srt, verbose_json, or vtt.
         */
        [
            'name' => 'response_format', RequiredValidator::NAME => false,
            'type' => EnumValidator::NAME, 'choices' => ['json', 'text', 'srt', 'verbose_json', 'vtt'],
        ],

        /**
         * temperature integer Optional Defaults to 0
         *
         * The sampling temperature, between 0 and 1. Higher values like 0.8 will
         * make the output more random, while lower values like 0.2 will make it
         * more focused and deterministic. If set to 0, the model will use log
         * probability to automatically increase the temperature until certain thresholds are hit.
         */
        ['name' => 'temperature', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME, 'min' => 0, 'max', 1],

        /**
         * language string Optional
         *
         * The language of the input audio. Supplying the input language in ISO-639-1 format will improve accuracy and latency.
         */
        ['name' => 'language', RequiredValidator::NAME => false, 'type' => StringValidator::NAME, 'min' => 2, 'max', 6],
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
