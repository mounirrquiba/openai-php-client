<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;

/**
 * Manage fine-tuning jobs to tailor a model to your specific training data.
 *
 * Related guide: https://platform.openai.com/docs/guides/fine-tuning
 */
final class FineTune extends AbstractService
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
    public const OPENAI_ENDPOINT = '/fine-tunes/{' . self::OPENAI_ID_NAME . '}';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_METHOD_TYPE = 'GET';

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
