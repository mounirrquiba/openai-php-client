<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;

final class FineTuneCancel extends AbstractService
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
    public const OPENAI_ENDPOINT = '/fine-tunes/{' . self::OPENAI_ID_NAME . '}/cancel';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_METHOD_TYPE = 'POST';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PATH_PARAMETERS = [];

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
