<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Validators\BooleanValidator;
use MounirRquiba\OpenAi\Validators\EnumValidator;
use MounirRquiba\OpenAi\Validators\IntegerValidator;
use MounirRquiba\OpenAi\Validators\MapValidator;
use MounirRquiba\OpenAi\Validators\MessagesValidator;
use MounirRquiba\OpenAi\Validators\NumberValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringOrArrayValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;

/**
 * Given a list of messages describing a conversation, the model will return a response.
 *
 * Creates a model response for the given chat conversation.
 */
final class Chat extends AbstractService
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
    public const OPENAI_ENDPOINT = '/chat/completions';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [
        /**
         * model string Required
         *
         * ID of the model to use.
         */
        ['name' => 'model', RequiredValidator::NAME => true, 'type' => StringValidator::NAME],

        /**
         * messages array Required
         *
         * A list of messages describing the conversation so far.
         */
        [
            'name' => MessagesValidator::NAME, RequiredValidator::NAME => true, 'type' => MessagesValidator::NAME,
            'fields' => [
                /**
                 * role string Required
                 *
                 * The role of the author of this message. One of system, user, or assistant.
                 */
                [ 'name' => 'role', RequiredValidator::NAME => true, 'type' => EnumValidator::NAME, 'choices' => ['system', 'user', 'assistant']],

                /**
                 * content string Required
                 *
                 * The contents of the message.
                 */
                [ 'name' => 'content', RequiredValidator::NAME => true, 'type' => StringValidator::NAME ],

                /**
                 * name string Optional
                 *
                 * The name of the author of this message. May contain a-z, A-Z, 0-9,
                 * and underscores, with a maximum length of 64 characters.
                 */
                [ 'name' => 'name', RequiredValidator::NAME => false, 'type' => StringValidator::NAME ],
            ],
        ],

        /**
         * @max_tokens integer Optional Defaults to 16
         *
         * The maximum number of tokens to generate in the completion.
         *
         * The token count of your prompt plus max_tokens cannot exceed the model's context length.
         * Most models have a context length of 2048 tokens (except for the newest models, which support 4096).
         */
        ['name' => 'max_tokens', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME, 'default' => 16],

        /**
         * @temperature integer Optional Defaults to 1
         *
         * What sampling temperature to use, between 0 and 2.
         * Higher values like 0.8 will make the output more random,
         * while lower values like 0.2 will make it more focused and deterministic.
         *
         * We generally recommend altering this or top_p but not both.
         */
        ['name' => 'temperature', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME, 'default' => 1],

        /**
         * top_p integer Optional Defaults to 1
         *
         * An alternative to sampling with temperature, called nucleus sampling, where the model considers
         * the results of the tokens with top_p probability mass. So 0.1 means only the tokens comprising
         * the top 10% probability mass are considered.
         *
         * We generally recommend altering this or temperature but not both.
         */
        ['name' => 'top_p', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME, 'default' => 1],

        /**
         * n integer Optional Defaults to 1
         *
         * How many chat completion choices to generate for each input message.
         */
        ['name' => 'n', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME, 'default' => 1],

        /**
         * stream boolean Optional Defaults to false
         *
         * Whether to stream back partial progress. If set,
         * tokens will be sent as data-only server-sent events as they become available,
         * with the stream terminated by a data: [DONE] message.
         *
         * https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events#Event_stream_format
         */
        ['name' => 'stream', RequiredValidator::NAME => false, 'type' => BooleanValidator::NAME, 'default' => false],

        /**
         * stop string or array Optional Defaults to null
         *
         * Up to 4 sequences where the API will stop generating further tokens.
         * The returned text will not contain the stop sequence.
         */
        ['name' => 'stop', RequiredValidator::NAME => false, 'type' => StringOrArrayValidator::NAME, 'default' => null, 'maxcount' => 4],

        /**
         * presence_penalty number Optional Defaults to 0
         *
         * Number between -2.0 and 2.0.
         * Positive values penalize new tokens based on whether they appear in the text so far,
         * increasing the model's likelihood to talk about new topics.
         *
         * Details: https://platform.openai.com/docs/api-reference/parameter-details
         *
         */
        ['name' => 'presence_penalty', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME, 'default' => 0, 'min' => -2.0, 'max' => 2.0],

        /**
         * frequency_penalty number Optional Defaults to 0
         *
         * Number between -2.0 and 2.0.
         * Positive values penalize new tokens based on their existing frequency in the text so far,
         * decreasing the model's likelihood to repeat the same line verbatim.
         *
         * Details: https://platform.openai.com/docs/api-reference/parameter-details
         *
         */
        ['name' => 'frequency_penalty', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME, 'default' => 0],

        /**
         *	logit_bias map Optional Defaults to null
         *
         *	Modify the likelihood of specified tokens appearing in the completion.
         *
         *	Accepts a json object that maps tokens (specified by their token ID in the GPT tokenizer)
         *	to an associated bias value from -100 to 100. You can use this tokenizer tool (which works
         *	for both GPT-2 and GPT-3) to convert text to token IDs. Mathematically, the bias is added
         *	to the logits generated by the model prior to sampling. The exact effect will vary per model,
         *	but values between -1 and 1 should decrease or increase likelihood of selection;
         *	values like -100 or 100 should result in a ban or exclusive selection of the relevant token.
         *
         *	As an example, you can pass {"50256": -100} to prevent the <|endoftext|> token from being generated.
         *
         *	Tokenizer tool: https://platform.openai.com/tokenizer?view=bpe
         */
        ['name' => 'logit_bias', RequiredValidator::NAME => false, 'type' => MapValidator::NAME, 'default' => null],

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
