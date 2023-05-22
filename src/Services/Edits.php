<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Validators\EnumValidator;
use MounirRquiba\OpenAi\Validators\IntegerValidator;
use MounirRquiba\OpenAi\Validators\NumberValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;

/**
 * Given a prompt and an instruction, the model will return an edited version of the prompt.
 *
 * Creates a new edit for the provided input, instruction, and parameters.
 */
final class Edits extends AbstractService
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
    public const OPENAI_ENDPOINT = '/edits';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [
        /**
         * model string Required
         *
         * ID of the model to use. You can use the text-davinci-edit-001 or code-davinci-edit-001 model with this endpoint.
         */
        ['name' => 'model', RequiredValidator::NAME => true, 'type' => EnumValidator::NAME, 'choices' => ['text-davinci-edit-001', 'code-davinci-edit-001']],

        /**
         * input string Optional Defaults to ''
         *
         * The input text to use as a starting point for the edit.
         */
        ['name' => 'input', RequiredValidator::NAME => false, 'type' => StringValidator::NAME],

        /**
         * instruction string Optional Defaults to ''
         *
         * The instruction that tells the model how to edit the prompt.
         */
        ['name' => 'instruction', RequiredValidator::NAME => true, 'type' => StringValidator::NAME],

        /**
         * n integer Optional Defaults to 1
         *
         * How many edits to generate for the input and instruction.
         */
        ['name' => 'n', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME, 'max' => 10, 'min' => 1],

        /**
         * temperature integer Optional Defaults to 1
         *
         * What sampling temperature to use, between 0 and 2.
         * Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic.
         * We generally recommend altering this or top_p but not both.
         */
        ['name' => 'temperature', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME, 'default' => 1],

        /**
         * top_p integer Optional Defaults to 1
         *
         * An alternative to sampling with temperature, called nucleus sampling,
         * where the model considers the results of the tokens with top_p probability mass.
         * So 0.1 means only the tokens comprising the top 10% probability mass are considered.
         *
         * We generally recommend altering this or temperature but not both.
         */
        ['name' => 'top_p', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME, 'default' => 1],
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
