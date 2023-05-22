<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Validators\BooleanValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;

final class FineTuneEvents extends AbstractService
{
    /**
     * {@inheritdoc}
     */
    public const OPENAI_DECODE_RESPONSE = true;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ID_NAME = 'fine_tune_id';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ENDPOINT = '/fine-tunes/{' . self::OPENAI_ID_NAME . '}/events';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PATH_PARAMETERS = [
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
    ];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_METHOD_TYPE = 'GET';

    public function setFineTune(string $fineTune): self
    {
        $this->setId($fineTune);

        return $this;
    }

    public function getFineTune(): string|null
    {
        return $this->getId();
    }
}
